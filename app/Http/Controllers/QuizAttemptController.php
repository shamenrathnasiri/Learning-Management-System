<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttemptQuizRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizAttemptController extends Controller
{
    public function store(AttemptQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();
        $isPrivilegedUser = $currentUser->isAdministrator() || $currentUser->isTutor();
        $existingAttempts = $quiz->attempts()->where('user_id', $currentUser->id)->count();

        if (! $isPrivilegedUser && $quiz->max_attempts !== null && $existingAttempts >= $quiz->max_attempts) {
            return redirect()
                ->route('quizzes.show', $quiz)
                ->withErrors(['attempts' => 'You have reached the maximum number of attempts for this quiz.']);
        }

        $quiz->load('questions');
        $answers = $request->validated('answers');
        $totalQuestions = $quiz->questions->count();
        $score = 0;

        $attempt = DB::transaction(function () use ($quiz, $answers, $totalQuestions, &$score, $request) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $request->user()->id,
                'score' => 0,
                'total_questions' => $totalQuestions,
                'percentage' => 0,
                'completed_at' => now(),
            ]);

            foreach ($quiz->questions as $question) {
                $selectedOption = (int) ($answers[$question->id] ?? -1);
                $isCorrect = $selectedOption === (int) $question->correct_option;

                if ($isCorrect) {
                    $score++;
                }

                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                ]);
            }

            $attempt->update([
                'score' => $score,
                'percentage' => $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0,
            ]);

            return $attempt;
        });

        $canViewResultNow = $isPrivilegedUser || $this->studentCanViewResult(
            $quiz,
            $existingAttempts + 1
        );

        if ($canViewResultNow) {
            return redirect()->route('quiz-attempts.show', $attempt)->with('status', 'Quiz submitted successfully.');
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', 'Quiz submitted successfully. Results are currently hidden by quiz settings.');
    }

    public function show(QuizAttempt $quizAttempt): View
    {
        $quizAttempt->load(['quiz.lesson', 'quiz.questions', 'student', 'answers.question']);
        /** @var User $currentUser */
        $currentUser = Auth::user();

        abort_unless(
            $currentUser->isAdministrator() || Auth::id() === $quizAttempt->user_id || $currentUser->isTutor(),
            403
        );

        $isPrivilegedUser = $currentUser->isAdministrator() || $currentUser->isTutor();

        if (! $isPrivilegedUser && Auth::id() === $quizAttempt->user_id) {
            $attemptNumber = $quizAttempt->quiz
                ->attempts()
                ->where('user_id', $quizAttempt->user_id)
                ->where('id', '<=', $quizAttempt->id)
                ->count();

            abort_unless($this->studentCanViewResult($quizAttempt->quiz, $attemptNumber), 403);
        }

        return view('quizzes.result', compact('quizAttempt'));
    }

    private function studentCanViewResult(Quiz $quiz, int $attemptNumber): bool
    {
        return match ($quiz->result_visibility) {
            'hidden' => false,
            'after_last_attempt' => $quiz->max_attempts !== null
                ? $attemptNumber >= $quiz->max_attempts
                : true,
            default => true,
        };
    }
}
