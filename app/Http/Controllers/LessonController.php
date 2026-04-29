<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::query()->with(['tutor', 'quiz.questions'])->latest()->paginate(12);

        return view('lessons.index', compact('lessons'));
    }

    public function create(): View
    {
        return view('lessons.create', [
            'courses' => [
                1 => 'Computer Science',
                2 => 'Mathematics',
                3 => 'Web Development',
                4 => 'Data Science',
            ],
        ]);
    }

    public function store(StoreLessonRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $lesson = new Lesson();
        $attachmentPaths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $attachmentPaths[] = $attachment->store('lesson-attachments', 'public');
            }
        }

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson-videos', 'public');
        }

        $lesson->fill([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(6)),
            'course_id' => $data['course_id'],
            'module' => $data['module'],
            'description' => $data['description'],
            'content' => $data['description'],
            'content_type' => $videoPath || $data['video_url'] ? 'video' : ($attachmentPaths ? 'file' : 'text'),
            'video_url' => $data['video_url'] ?? null,
            'video_path' => $videoPath,
            'duration' => $data['duration'],
            'release_date' => $data['release_date'],
            'status' => $data['status'],
            'attachment_paths' => $attachmentPaths ?: null,
            'attachment_path' => $attachmentPaths[0] ?? null,
        ]);

        if ($request->hasFile('thumbnail')) {
            $lesson->thumbnail_path = $request->file('thumbnail')->store('lesson-thumbnails', 'public');
        }

        $lesson->save();

        return redirect()->route('lessons.create')->with('status', $data['status'] === 'published' ? 'Lesson published successfully.' : 'Lesson saved as draft.');
    }

    public function show(Lesson $lesson): View
    {
        $lesson->load(['tutor', 'quiz.questions', 'quiz.attempts.student']);

        return view('lessons.show', compact('lesson'));
    }

    public function edit(Lesson $lesson): View
    {
        return view('lessons.edit', compact('lesson'));
    }

    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $data = $request->validated();

        $lesson->fill([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(6)),
            'description' => $data['description'],
            'content_type' => $data['content_type'],
            'content' => $data['content'] ?? null,
            'video_url' => $data['video_url'] ?? null,
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($lesson->thumbnail_path) {
                Storage::disk('public')->delete($lesson->thumbnail_path);
            }

            $lesson->thumbnail_path = $request->file('thumbnail')->store('lesson-thumbnails', 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($lesson->attachment_path) {
                Storage::disk('public')->delete($lesson->attachment_path);
            }

            $lesson->attachment_path = $request->file('attachment')->store('lesson-files', 'public');
        }

        $lesson->save();

        return redirect()->route('lessons.show', $lesson)->with('status', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        if ($lesson->thumbnail_path) {
            Storage::disk('public')->delete($lesson->thumbnail_path);
        }

        if ($lesson->attachment_path) {
            Storage::disk('public')->delete($lesson->attachment_path);
        }

        $lesson->delete();

        return redirect()->route('lessons.index')->with('status', 'Lesson deleted successfully.');
    }
}
