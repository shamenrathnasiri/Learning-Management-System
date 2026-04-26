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

        return redirect()->route('quiz-attempts.show', $attempt)->with('status', 'Quiz submitted successfully.');
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

        return view('quizzes.result', compact('quizAttempt'));
    }
}
