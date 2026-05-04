<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show all quiz attempts needing grading for a quiz.
     */
    public function indexAttempts(Quiz $quiz)
    {
        // Check if user is tutor or admin of this quiz
        $user = Auth::user();
        abort_unless(
            $user->isTutor() || $user->isAdministrator(),
            403
        );

        $attempts = $quiz->attempts()
            ->with(['student', 'answers.question'])
            ->orderByDesc('completed_at')
            ->paginate(15);

        // Calculate pending grades for each attempt
        $attempts->each(function ($attempt) {
            $attempt->pending_grades = $attempt->answers
                ->filter(fn($answer) => $answer->needsGrading())
                ->count();

            $attempt->graded_count = $attempt->answers
                ->filter(fn($answer) => $answer->isGraded())
                ->count();
        });

        return view('quizzes.grading.attempts', compact('quiz', 'attempts'));
    }

    /**
     * Show grading interface for a specific attempt.
     */
    public function show(QuizAttempt $attempt)
    {
        $user = Auth::user();
        abort_unless(
            $user->isTutor() || $user->isAdministrator(),
            403
        );

        $attempt->load(['student', 'quiz', 'answers.question', 'answers.gradedByUser']);

        $subjective_answers = $attempt->answers
            ->filter(fn($answer) => in_array($answer->question->type, ['essay', 'short_answer']))
            ->values();

        $objective_answers = $attempt->answers
            ->filter(fn($answer) => !in_array($answer->question->type, ['essay', 'short_answer']))
            ->values();

        // Calculate current score
        $objective_score = $objective_answers->sum(fn($a) => $a->is_correct ? $a->question->marks : 0);
        $subjective_score = $subjective_answers
            ->filter(fn($a) => $a->marks_obtained !== null)
            ->sum(fn($a) => $a->marks_obtained);

        $pending_subjective = $subjective_answers->filter(fn($a) => $a->needsGrading())->count();
        $graded_subjective = $subjective_answers->filter(fn($a) => !$a->needsGrading())->count();

        return view('quizzes.grading.show', compact(
            'attempt',
            'subjective_answers',
            'objective_answers',
            'objective_score',
            'subjective_score',
            'pending_subjective',
            'graded_subjective'
        ));
    }

    /**
     * Grade a single answer (AJAX/Form endpoint).
     */
    public function grade(Request $request, QuizAttemptAnswer $answer)
    {
        $user = Auth::user();
        abort_unless(
            $user->isTutor() || $user->isAdministrator(),
            403
        );

        $validated = $request->validate([
            'marks_obtained' => 'required|integer|min:0|max:' . $answer->question->marks,
            'tutor_feedback' => 'nullable|string|max:2000',
        ]);

        $answer->update([
            'marks_obtained' => $validated['marks_obtained'],
            'tutor_feedback' => $validated['tutor_feedback'] ?? null,
            'graded_at' => now(),
            'graded_by' => Auth::id(),
            'status' => 'graded',
        ]);

        // Recalculate attempt score
        $this->recalculateAttemptScore($answer->attempt);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Answer graded successfully',
                'answer' => $answer->load('gradedByUser'),
            ]);
        }

        return redirect()->back()->with('success', 'Answer graded successfully');
    }

    /**
     * Recalculate total score for an attempt.
     */
    protected function recalculateAttemptScore(QuizAttempt $attempt)
    {
        $attempt->load('answers.question');

        $total_marks = $attempt->answers->sum(function ($answer) {
            if ($answer->question->type === 'essay') {
                return $answer->marks_obtained ?? 0;
            } elseif (in_array($answer->question->type, ['multiple_choice', 'true_false'])) {
                return $answer->is_correct ? $answer->question->marks : 0;
            } elseif ($answer->question->type === 'short_answer') {
                return $answer->marks_obtained ?? 0;
            }
            return 0;
        });

        $total_possible = $attempt->answers->sum(fn($a) => $a->question->marks);
        $percentage = $total_possible > 0 ? round(($total_marks / $total_possible) * 100, 2) : 0;

        $attempt->update([
            'score' => $total_marks,
            'percentage' => $percentage,
        ]);
    }

    /**
     * Bulk grade essays for an attempt.
     */
    public function bulkGrade(Request $request, QuizAttempt $attempt)
    {
        $user = Auth::user();
        abort_unless(
            $user->isTutor() || $user->isAdministrator(),
            403
        );

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'array',
            'grades.*.marks_obtained' => 'required|integer|min:0',
            'grades.*.tutor_feedback' => 'nullable|string|max:2000',
        ]);

        foreach ($validated['grades'] as $answer_id => $gradeData) {
            $answer = QuizAttemptAnswer::findOrFail($answer_id);
            abort_unless(
                $user->isTutor() || $user->isAdministrator(),
                403
            );

            $answer->update([
                'marks_obtained' => $gradeData['marks_obtained'],
                'tutor_feedback' => $gradeData['tutor_feedback'] ?? null,
                'graded_at' => now(),
                'graded_by' => Auth::id(),
                'status' => 'graded',
            ]);
        }

        // Recalculate score once
        $this->recalculateAttemptScore($attempt);

        return redirect()->back()->with('success', 'All answers graded successfully');
    }
}
