<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $currentUser = auth()->user();
        $isPrivilegedViewer = $currentUser?->isTutor() || $currentUser?->isAdministrator();
        $attemptCount = auth()->check()
            ? $quiz->attempts()->where('user_id', auth()->id())->count()
            : 0;

        $attemptLimitReached = ! $isPrivilegedViewer
            && $quiz->max_attempts !== null
            && $attemptCount >= $quiz->max_attempts;

        $questions = $quiz->questions;

        if ($quiz->shuffle_questions && ! $isPrivilegedViewer) {
            $questions = $questions->shuffle()->values();
        }

        $presentedQuestions = $questions->map(function ($question) use ($quiz, $isPrivilegedViewer) {
            $options = collect([
                ['value' => 0, 'text' => $question->option_one],
                ['value' => 1, 'text' => $question->option_two],
                ['value' => 2, 'text' => $question->option_three],
                ['value' => 3, 'text' => $question->option_four],
            ]);

            if ($quiz->shuffle_answers && ! $isPrivilegedViewer) {
                $options = $options->shuffle()->values();
            }

            return [
                'question' => $question,
                'options' => $options,
            ];
        });

        return view('quizzes.show', [
            'quiz' => $quiz,
            'presentedQuestions' => $presentedQuestions,
            'attemptCount' => $attemptCount,
            'attemptLimitReached' => $attemptLimitReached,
        ]);
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
        $pathsToDelete = [];

        DB::transaction(function () use ($request, $quiz, $data, &$pathsToDelete): void {
            $pathsToDelete = $this->syncQuiz($request, $quiz, $data);
        });

        $this->deleteMediaPaths($pathsToDelete);

        return redirect()->route('quizzes.show', $quiz)->with('status', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->deleteQuestionMedia($quiz->questions);

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
            'shuffle_questions' => (bool) ($data['shuffle_questions'] ?? false),
            'shuffle_answers' => (bool) ($data['shuffle_answers'] ?? false),
            'max_attempts' => $data['max_attempts'] ?? null,
            'result_visibility' => $data['result_visibility'] ?? 'immediate',
            'show_correct_answers' => (bool) ($data['show_correct_answers'] ?? false),
            'show_explanations' => (bool) ($data['show_explanations'] ?? false),
        ]);

        $this->persistQuestions($request, $quiz, $data);

        return $quiz;
    }

    private function syncQuiz(QuizRequest $request, Quiz $quiz, array $data): array
    {
        $pathsToDelete = $this->questionMediaPathsToDelete($quiz, $request);

        $quiz->update([
            'course_id' => $data['course_id'] ?? $quiz->course_id,
            'lesson_id' => $data['lesson_id'] ?? $quiz->lesson_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'instructions' => $data['instructions'] ?? null,
            'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
            'total_marks' => $data['total_marks'],
            'passing_score' => $data['passing_score'],
            'shuffle_questions' => (bool) ($data['shuffle_questions'] ?? false),
            'shuffle_answers' => (bool) ($data['shuffle_answers'] ?? false),
            'max_attempts' => $data['max_attempts'] ?? null,
            'result_visibility' => $data['result_visibility'] ?? 'immediate',
            'show_correct_answers' => (bool) ($data['show_correct_answers'] ?? false),
            'show_explanations' => (bool) ($data['show_explanations'] ?? false),
        ]);

        $quiz->questions()->delete();

        $this->persistQuestions($request, $quiz, $data);

        return $pathsToDelete;
    }

    private function persistQuestions(QuizRequest $request, Quiz $quiz, array $data): void
    {
        foreach ($data['questions'] as $index => $questionData) {
            $quiz->questions()->create($this->buildQuestionPayload($request, $questionData, $index));
        }
    }

    private function buildQuestionPayload(QuizRequest $request, array $questionData, int $index): array
    {
        $mediaFile = $request->file("questions.$index.media");
        $existingMediaPath = $request->input("questions.$index.existing_media_path");
        $existingMediaType = $request->input("questions.$index.existing_media_type");
        $existingMediaName = $request->input("questions.$index.existing_media_name");

        $mediaPath = $existingMediaPath;
        $mediaType = $existingMediaType;
        $mediaName = $existingMediaName;

        if ($mediaFile) {
            $mediaPath = $mediaFile->store('question-media', 'public');
            $mediaType = $mediaFile->getMimeType();
            $mediaName = $mediaFile->getClientOriginalName();
        }

        return [
            'sort_order' => $index,
            'type' => $questionData['type'],
            'question' => $questionData['question'],
            'difficulty' => $questionData['difficulty'] ?? 'medium',
            'tags' => $this->normalizeTags($questionData['tags'] ?? null),
            'option_one' => $questionData['option_one'] ?? null,
            'option_two' => $questionData['option_two'] ?? null,
            'option_three' => $questionData['option_three'] ?? null,
            'option_four' => $questionData['option_four'] ?? null,
            'correct_option' => $questionData['correct_option'] ?? 0,
            'marks' => $questionData['marks'] ?? 1,
            'explanation' => $questionData['explanation'] ?? null,
            'correct_answer' => $questionData['correct_answer'] ?? null,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'media_name' => $mediaName,
        ];
    }

    private function normalizeTags(mixed $tags): array
    {
        if (is_array($tags)) {
            return array_values(array_filter(array_map(static fn ($tag) => trim((string) $tag), $tags)));
        }

        if (is_string($tags) && trim($tags) !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $tags))));
        }

        return [];
    }

    private function questionMediaPathsToDelete(Quiz $quiz, QuizRequest $request): array
    {
        $existingPaths = $quiz->questions->pluck('media_path')->filter()->values()->all();
        $submittedQuestions = $request->input('questions', []);
        $keptPaths = [];
        $replacedPaths = [];

        foreach ($submittedQuestions as $index => $questionData) {
            $currentPath = $questionData['existing_media_path'] ?? null;

            if ($currentPath) {
                $keptPaths[] = $currentPath;
            }

            if ($request->hasFile("questions.$index.media") && $currentPath) {
                $replacedPaths[] = $currentPath;
            }
        }

        return array_values(array_unique(array_filter(array_merge(
            array_diff($existingPaths, $keptPaths),
            $replacedPaths,
        ))));
    }

    private function deleteMediaPaths(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function deleteQuestionMedia($questions): void
    {
        foreach ($questions as $question) {
            if ($question->media_path) {
                Storage::disk('public')->delete($question->media_path);
            }
        }
    }
}
