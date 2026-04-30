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
    public function createHub(): View
    {
        $lessons = Lesson::query()
            ->with(['tutor', 'quiz.questions'])
            ->latest()
            ->get();

        $recentQuizzes = Quiz::query()
            ->with(['lesson.tutor', 'questions'])
            ->latest()
            ->take(6)
            ->get();

        $courses = [
            1 => 'Computer Science',
            2 => 'Mathematics',
            3 => 'Web Development',
            4 => 'Data Science',
        ];

        return view('quizzes.hub', compact('lessons', 'recentQuizzes', 'courses'));
    }

    public function create(Lesson $lesson): View
    {
        return view('quizzes.create', [
            'lesson' => $lesson,
            'courses' => [
                1 => 'Computer Science',
                2 => 'Mathematics',
                3 => 'Web Development',
                4 => 'Data Science',
            ],
        ]);
    }

    public function storeHub(QuizRequest $request): RedirectResponse
    {
        $quiz = $this->persistQuiz($request);

        return redirect()->route('quizzes.show', $quiz)->with('status', 'Quiz created successfully.');
    }

    public function store(QuizRequest $request, Lesson $lesson): RedirectResponse
    {
        $quiz = $this->persistQuiz($request, $lesson);

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

        return view('quizzes.edit', [
            'quiz' => $quiz,
            'courses' => [
                1 => 'Computer Science',
                2 => 'Mathematics',
                3 => 'Web Development',
                4 => 'Data Science',
            ],
            'lessons' => Lesson::query()->latest()->get(),
        ]);
    }

    public function update(QuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $data = $request->validated();

        $quiz->update([
            'course_id' => $data['course_id'] ?? $quiz->course_id,
            'lesson_id' => $data['lesson_id'] ?? $quiz->lesson_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'instructions' => $data['instructions'] ?? null,
            'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
            'total_marks' => $data['total_marks'],
            'passing_score' => $data['passing_score'],
        ]);

        $quiz->questions()->delete();

        foreach ($data['questions'] as $questionData) {
            $quiz->questions()->create($questionData);
        }

        return redirect()->route('quizzes.show', $quiz)->with('status', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $lesson = $quiz->lesson;
        $quiz->delete();

        if ($lesson) {
            return redirect()->route('lessons.show', $lesson)->with('status', 'Quiz deleted successfully.');
        }

        return redirect()->route('quizzes.create')->with('status', 'Quiz deleted successfully.');
    }

    private function persistQuiz(QuizRequest $request, ?Lesson $lesson = null): Quiz
    {
        $data = $request->validated();
        $selectedLessonId = $lesson?->id ?? ($data['lesson_id'] ?? null);
        $selectedCourseId = $data['course_id'] ?? $lesson?->course_id ?? null;

        $quiz = Quiz::create([
            'course_id' => $selectedCourseId,
            'lesson_id' => $selectedLessonId,
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'instructions' => $data['instructions'] ?? null,
            'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
            'total_marks' => $data['total_marks'],
            'passing_score' => $data['passing_score'],
        ]);

        foreach ($data['questions'] as $questionData) {
            $quiz->questions()->create($questionData);
        }

        return $quiz;
    }
}
