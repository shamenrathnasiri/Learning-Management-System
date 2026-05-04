<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttemptQuizRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Carbon;
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

        if (! $isPrivilegedUser && ! $this->canAccessQuiz($quiz, $currentUser)) {
            return redirect()
                ->route('quizzes.show', $quiz)
                ->withErrors(['access' => 'You cannot access this quiz at this time.']);
        }

        if (! $isPrivilegedUser && $quiz->max_attempts !== null && $existingAttempts >= $quiz->max_attempts) {
            return redirect()
                ->route('quizzes.show', $quiz)
                ->withErrors(['attempts' => 'You have reached the maximum number of attempts for this quiz.']);
        }

        if (! $isPrivilegedUser && $quiz->time_limit_minutes) {
            $attemptStartedAt = $request->validated('attempt_started_at');

            if (! $attemptStartedAt) {
                return redirect()
                    ->route('quizzes.show', $quiz)
                    ->withErrors(['timer' => 'This timed quiz session has expired. Please start again.']);
            }

            $startedAt = Carbon::parse($attemptStartedAt);
            $secondsElapsed = $startedAt->diffInSeconds(now(), false);

            if ($secondsElapsed > ($quiz->time_limit_minutes * 60)) {
                return redirect()
                    ->route('quizzes.show', $quiz)
                    ->withErrors(['timer' => 'Time limit reached. Your attempt was not accepted from this session.']);
            }
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

                // Auto-grade objective questions
                if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                    $isCorrect = $selectedOption === (int) $question->correct_option;
                    if ($isCorrect) {
                        $score += $question->marks;
                    }

                    $attempt->answers()->create([
                        'question_id' => $question->id,
                        'selected_option' => $selectedOption,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $isCorrect ? $question->marks : 0,
                        'status' => 'graded',
                    ]);
                }
                // Short answer questions - exact match auto-grading
                elseif ($question->type === 'short_answer') {
                    $userAnswer = trim($answers[$question->id] ?? '');
                    $correctAnswer = trim($question->correct_answer ?? '');
                    $isCorrect = strtolower($userAnswer) === strtolower($correctAnswer);

                    if ($isCorrect) {
                        $score += $question->marks;
                    }

                    $attempt->answers()->create([
                        'question_id' => $question->id,
                        'selected_option' => 0,
                        'text_answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $isCorrect ? $question->marks : 0,
                        'status' => 'graded',
                    ]);
                }
                // Essay questions - needs tutor grading
                elseif ($question->type === 'essay') {
                    $userAnswer = trim($answers[$question->id] ?? '');

                    $attempt->answers()->create([
                        'question_id' => $question->id,
                        'selected_option' => 0,
                        'text_answer' => $userAnswer,
                        'is_correct' => false,
                        'marks_obtained' => null,
                        'status' => 'pending',
                    ]);
                } else {
                    // Default: treat as objective
                    $isCorrect = $selectedOption === (int) $question->correct_option;
                    if ($isCorrect) {
                        $score += $question->marks;
                    }

                    $attempt->answers()->create([
                        'question_id' => $question->id,
                        'selected_option' => $selectedOption,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $isCorrect ? $question->marks : 0,
                        'status' => 'graded',
                    ]);
                }
            }

            $totalMarks = $quiz->questions->sum('marks');
            $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;

            $attempt->update([
                'score' => $score,
                'percentage' => $percentage,
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

    private function canAccessQuiz(Quiz $quiz, User $user): bool
    {
        if (! $quiz->is_published) {
            return false;
        }

        if ($quiz->starts_at instanceof Carbon && now()->lt($quiz->starts_at)) {
            return false;
        }

        if ($quiz->ends_at instanceof Carbon && now()->gt($quiz->ends_at)) {
            return false;
        }

        if ($quiz->restrict_to_enrolled_students && $quiz->lesson_id && ! $user->isEnrolledInLesson($quiz->lesson_id)) {
            return false;
        }

        return true;
    }
}
