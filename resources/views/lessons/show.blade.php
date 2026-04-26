<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lesson details</p>
                <h2 class="text-2xl font-bold text-black">{{ $lesson->title }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                    <a href="{{ route('lessons.edit', $lesson) }}" class="lms-button-secondary">Edit lesson</a>
                    <a href="{{ route('lessons.quizzes.create', $lesson) }}" class="lms-button">Add quiz</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <article class="lms-card overflow-hidden">
                @if ($lesson->thumbnail_path)
                    <img src="{{ asset('storage/'.$lesson->thumbnail_path) }}" alt="{{ $lesson->title }}" class="h-80 w-full object-cover">
                @else
                    <div class="flex h-80 items-center justify-center bg-gradient-to-br from-black to-[#E50914] text-6xl font-black text-white">{{ strtoupper(substr($lesson->title, 0, 1)) }}</div>
                @endif

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-3 text-xs uppercase tracking-[0.3em] text-black/40">
                        <span>{{ $lesson->content_type }}</span>
                        <span>{{ $lesson->tutor->name ?? 'Tutor' }}</span>
                    </div>
                    <p class="mt-4 text-base leading-7 text-black/70">{{ $lesson->description }}</p>

                    <div class="mt-6 rounded-3xl bg-black/5 p-5">
                        <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-black/50">Content</h3>
                        @if ($lesson->content_type === 'video' && $lesson->video_url)
                            <a href="{{ $lesson->video_url }}" target="_blank" class="mt-3 inline-flex text-sm font-semibold text-[#E50914]">Open video link</a>
                            @if ($lesson->content)
                                <p class="mt-4 whitespace-pre-line text-black/70">{{ $lesson->content }}</p>
                            @endif
                        @elseif ($lesson->content_type === 'file' && $lesson->attachment_path)
                            <a href="{{ asset('storage/'.$lesson->attachment_path) }}" target="_blank" class="mt-3 inline-flex text-sm font-semibold text-[#E50914]">Download file</a>
                            @if ($lesson->content)
                                <p class="mt-4 whitespace-pre-line text-black/70">{{ $lesson->content }}</p>
                            @endif
                        @else
                            <p class="mt-3 whitespace-pre-line text-black/70">{{ $lesson->content ?: 'This lesson currently has no additional content block.' }}</p>
                        @endif
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                <div class="lms-card p-6">
                    <h3 class="text-lg font-bold text-black">Quiz</h3>
                    @if ($lesson->quiz)
                        <p class="mt-2 text-sm text-black/60">{{ $lesson->quiz->questions->count() }} questions, passing score {{ $lesson->quiz->passing_score }}%</p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="lms-button">Attempt quiz</a>
                            @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                                <a href="{{ route('quizzes.edit', $lesson->quiz) }}" class="lms-button-secondary">Edit quiz</a>
                            @endif
                        </div>
                    @else
                        <p class="mt-2 text-sm text-black/60">No quiz has been attached to this lesson yet.</p>
                        @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                            <div class="mt-5">
                                <a href="{{ route('lessons.quizzes.create', $lesson) }}" class="lms-button">Create quiz</a>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="lms-card p-6">
                    <h3 class="text-lg font-bold text-black">Lesson metadata</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between border-b border-black/10 pb-3">
                            <dt class="text-black/50">Author</dt>
                            <dd class="font-semibold text-black">{{ $lesson->tutor->name ?? 'Tutor' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-b border-black/10 pb-3">
                            <dt class="text-black/50">Created</dt>
                            <dd class="font-semibold text-black">{{ $lesson->created_at?->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-black/50">Updated</dt>
                            <dd class="font-semibold text-black">{{ $lesson->updated_at?->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>
