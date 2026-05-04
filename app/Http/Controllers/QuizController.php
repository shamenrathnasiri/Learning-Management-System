<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Notifications\QuizPublishedNotification;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $status = $quiz->is_published
            ? 'Quiz created and published successfully.'
            : 'Quiz saved as draft successfully.';

        return redirect()->route('quizzes.show', $quiz)->with('status', $status);
    }

    public function store(QuizRequest $request, Lesson $lesson): RedirectResponse
    {
        $quiz = $this->persistQuiz($request, $lesson);
        $status = $quiz->is_published
            ? 'Quiz published successfully.'
            : 'Quiz saved as draft successfully.';

        return redirect()->route('lessons.show', $lesson)->with('status', $status);
    }

    public function show(Quiz $quiz): View
    {
        $quiz->load(['lesson.tutor', 'questions']);

        $currentUser = Auth::user();
        $isPrivilegedViewer = $currentUser instanceof User
            ? ($currentUser->isTutor() || $currentUser->isAdministrator())
            : false;
        $attemptCount = Auth::check()
            ? $quiz->attempts()->where('user_id', Auth::id())->count()
            : 0;

        $attemptLimitReached = ! $isPrivilegedViewer
            && $quiz->max_attempts !== null
            && $attemptCount >= $quiz->max_attempts;

        $now = now();
        $startsInFuture = ! $isPrivilegedViewer
            && $quiz->starts_at instanceof Carbon
            && $now->lt($quiz->starts_at);
        $ended = ! $isPrivilegedViewer
            && $quiz->ends_at instanceof Carbon
            && $now->gt($quiz->ends_at);
        $enrollmentRequired = (bool) $quiz->restrict_to_enrolled_students;
        $hasLesson = $quiz->lesson_id !== null;
        $isEnrolled = ! $enrollmentRequired
            || ! $hasLesson
            || $isPrivilegedViewer
            || ($currentUser instanceof User && $currentUser->isEnrolledInLesson($quiz->lesson_id));

        $accessDenied = $startsInFuture || $ended || ! $isEnrolled;
        $isUnpublished = ! $isPrivilegedViewer && ! $quiz->is_published;
        $accessDenied = $accessDenied || $isUnpublished;
        $attemptStartedAt = $accessDenied ? null : now()->toIso8601String();

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
            'accessDenied' => $accessDenied,
            'startsInFuture' => $startsInFuture,
            'ended' => $ended,
            'isEnrolled' => $isEnrolled,
            'isUnpublished' => $isUnpublished,
            'attemptStartedAt' => $attemptStartedAt,
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

        if ((bool) ($data['publish_now'] ?? false)) {
            $notifiedStudents = $this->publishQuiz($quiz);

            return redirect()
                ->route('quizzes.show', $quiz)
                ->with('status', 'Quiz updated and published successfully.'.($notifiedStudents > 0 ? " {$notifiedStudents} student notifications sent." : ''));
        }

        return redirect()->route('quizzes.show', $quiz)->with('status', 'Quiz updated successfully.');
    }

    public function publish(Quiz $quiz): RedirectResponse
    {
        $notifiedStudents = $this->publishQuiz($quiz);

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', $quiz->wasChanged('is_published')
                ? 'Quiz is now live.'.($notifiedStudents > 0 ? " {$notifiedStudents} student notifications sent." : '')
                : 'Quiz is already live.');
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

    public function analytics(): View
    {
        $user = Auth::user();
        abort_unless($user instanceof User && in_array($user->role, ['tutor', 'admin'], true), 403);

        $quizzes = Quiz::query()
            ->when($user->role === 'tutor', fn ($query) => $query->where('user_id', $user->id))
            ->with([
                'lesson',
                'questions.answers',
                'attempts.quiz',
                'attempts.student',
                'attempts.answers.question',
            ])
            ->latest()
            ->get();

        $quizInsights = $quizzes
            ->map(fn (Quiz $quiz) => $this->buildQuizInsight($quiz))
            ->values();

        $allAttempts = $quizzes->flatMap(fn (Quiz $quiz) => $quiz->attempts)->values();
        $gradedAttempts = $allAttempts->filter(fn ($attempt) => $this->attemptIsFullyGraded($attempt));

        $summary = [
            'quizzes' => $quizInsights->count(),
            'attempts' => $allAttempts->count(),
            'graded_attempts' => $gradedAttempts->count(),
            'pending_attempts' => $allAttempts->count() - $gradedAttempts->count(),
            'average_score' => $gradedAttempts->count() ? round($gradedAttempts->avg('score'), 2) : 0,
            'average_percentage' => $gradedAttempts->count() ? round($gradedAttempts->avg('percentage'), 2) : 0,
            'pass_rate' => $gradedAttempts->count()
                ? round(($gradedAttempts->filter(fn ($attempt) => $attempt->percentage >= ($attempt->quiz?->passing_score ?? 0))->count() / $gradedAttempts->count()) * 100, 1)
                : 0,
            'fail_rate' => $gradedAttempts->count()
                ? round(($gradedAttempts->reject(fn ($attempt) => $attempt->percentage >= ($attempt->quiz?->passing_score ?? 0))->count() / $gradedAttempts->count()) * 100, 1)
                : 0,
            'highest_percentage' => $gradedAttempts->count() ? round($gradedAttempts->max('percentage'), 2) : 0,
        ];

        $recentAttempts = $allAttempts
            ->sortByDesc(fn ($attempt) => $attempt->completed_at ?? $attempt->created_at)
            ->take(8)
            ->values();

        return view('quizzes.analytics', compact('quizInsights', 'summary', 'recentAttempts'));
    }

    public function duplicate(Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user instanceof User && in_array($user->role, ['tutor', 'admin'], true), 403);

        $duplicate = DB::transaction(function () use ($quiz, $user): Quiz {
            $quiz->loadMissing('questions');

            $newQuiz = $quiz->replicate([
                'is_published',
                'published_at',
            ]);

            $newQuiz->fill([
                'title' => $quiz->title.' (Copy)',
                'user_id' => $user->id,
                'is_published' => false,
                'published_at' => null,
            ]);
            $newQuiz->save();

            foreach ($quiz->questions as $question) {
                $this->duplicateQuestion($question, $newQuiz);
            }

            return $newQuiz->load('lesson');
        });

        return redirect()
            ->route('quizzes.edit', $duplicate)
            ->with('status', 'Quiz duplicated successfully. Review the copy before publishing.');
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
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'restrict_to_enrolled_students' => (bool) ($data['restrict_to_enrolled_students'] ?? false),
            'auto_submit_on_expiry' => (bool) ($data['auto_submit_on_expiry'] ?? false),
        ]);

        $this->persistQuestions($request, $quiz, $data);

        if ((bool) ($data['publish_now'] ?? false)) {
            $this->publishQuiz($quiz);
        }

        return $quiz;
    }

    private function buildQuizInsight(Quiz $quiz): array
    {
        $attempts = $quiz->attempts->values();
        $gradedAttempts = $attempts->filter(fn ($attempt) => $this->attemptIsFullyGraded($attempt));
        $pendingAttempts = $attempts->count() - $gradedAttempts->count();

        return [
            'quiz' => $quiz,
            'attempts' => $attempts,
            'recent_attempts' => $attempts
                ->sortByDesc(fn ($attempt) => $attempt->completed_at ?? $attempt->created_at)
                ->take(5)
                ->values(),
            'metrics' => [
                'attempts' => $attempts->count(),
                'graded_attempts' => $gradedAttempts->count(),
                'pending_attempts' => $pendingAttempts,
                'average_score' => $gradedAttempts->count() ? round($gradedAttempts->avg('score'), 2) : 0,
                'average_percentage' => $gradedAttempts->count() ? round($gradedAttempts->avg('percentage'), 2) : 0,
                'pass_rate' => $gradedAttempts->count()
                    ? round(($gradedAttempts->filter(fn ($attempt) => $attempt->percentage >= $quiz->passing_score)->count() / $gradedAttempts->count()) * 100, 1)
                    : 0,
                'fail_rate' => $gradedAttempts->count()
                    ? round(($gradedAttempts->reject(fn ($attempt) => $attempt->percentage >= $quiz->passing_score)->count() / $gradedAttempts->count()) * 100, 1)
                    : 0,
                'highest_percentage' => $gradedAttempts->count() ? round($gradedAttempts->max('percentage'), 2) : 0,
            ],
            'questions' => $quiz->questions->map(fn (Question $question) => $this->buildQuestionInsight($question))->values(),
        ];
    }

    private function buildQuestionInsight(Question $question): array
    {
        $answers = $question->answers->values();
        $responseCount = $answers->count();
        $earnedMarks = $answers->sum(function ($answer) use ($question) {
            if ($answer->marks_obtained !== null) {
                return (int) $answer->marks_obtained;
            }

            return $answer->is_correct ? (int) $question->marks : 0;
        });

        $maxMarks = $responseCount * max(1, (int) $question->marks);

        return [
            'question' => $question,
            'responses' => $responseCount,
            'correct_responses' => $answers->filter(fn ($answer) => (bool) $answer->is_correct)->count(),
            'earned_marks' => $earnedMarks,
            'average_marks' => $responseCount ? round($earnedMarks / $responseCount, 2) : 0,
            'performance_rate' => $maxMarks ? round(($earnedMarks / $maxMarks) * 100, 1) : 0,
        ];
    }

    private function attemptIsFullyGraded($attempt): bool
    {
        return $attempt->answers->every(fn ($answer) => $answer->isGraded());
    }

    private function duplicateQuestion(Question $question, Quiz $quiz): void
    {
        $copiedQuestion = $question->replicate([
            'media_path',
            'media_type',
            'media_name',
        ]);

        if ($question->media_path && Storage::disk('public')->exists($question->media_path)) {
            $extension = pathinfo($question->media_path, PATHINFO_EXTENSION);
            $mediaDirectory = trim((string) dirname($question->media_path), '.');
            $duplicatePath = ($mediaDirectory !== '' ? $mediaDirectory.'/' : '')
                .'quiz-duplicate-'.Str::uuid().($extension !== '' ? '.'.$extension : '');

            Storage::disk('public')->copy($question->media_path, $duplicatePath);

            $copiedQuestion->media_path = $duplicatePath;
        }

        $copiedQuestion->quiz_id = $quiz->id;
        $copiedQuestion->sort_order = $question->sort_order;
        $copiedQuestion->save();
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
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'restrict_to_enrolled_students' => (bool) ($data['restrict_to_enrolled_students'] ?? false),
            'auto_submit_on_expiry' => (bool) ($data['auto_submit_on_expiry'] ?? false),
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

    private function publishQuiz(Quiz $quiz): int
    {
        if ($quiz->is_published) {
            return 0;
        }

        $quiz->forceFill([
            'is_published' => true,
            'published_at' => now(),
        ])->save();

        $quiz->loadMissing('lesson.enrolledStudents');
        $students = $quiz->lesson?->enrolledStudents
            ? $quiz->lesson->enrolledStudents->where('role', 'student')->values()
            : collect();

        if ($students->isEmpty()) {
            return 0;
        }

        foreach ($students as $student) {
            $student->notify(new QuizPublishedNotification($quiz));
        }

        return $students->count();
    }
}
