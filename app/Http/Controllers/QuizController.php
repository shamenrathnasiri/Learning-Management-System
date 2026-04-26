<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function create(Lesson $lesson): View
    {
        return view('quizzes.create', compact('lesson'));
    }

    public function store(QuizRequest $request, Lesson $lesson): RedirectResponse
    {
        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'user_id' => $request->user()->id,
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'passing_score' => $request->validated('passing_score'),
        ]);

        foreach ($request->validated('questions') as $questionData) {
            $quiz->questions()->create($questionData);
        }

        return redirect()->route('lessons.show', $lesson)->with('status', 'Quiz published successfully.');
    }

    public function show(Quiz $quiz): View
    {
        $quiz->load(['lesson.tutor', 'questions']);

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz): View
    {
        $quiz->load('questions', 'lesson');

        return view('quizzes.edit', compact('quiz'));
    }

    public function update(QuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $quiz->update([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'passing_score' => $request->validated('passing_score'),
        ]);

        $quiz->questions()->delete();

        foreach ($request->validated('questions') as $questionData) {
            $quiz->questions()->create($questionData);
        }

        return redirect()->route('quizzes.show', $quiz)->with('status', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $lesson = $quiz->lesson;
        $quiz->delete();

        return redirect()->route('lessons.show', $lesson)->with('status', 'Quiz deleted successfully.');
    }
}
