<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lesson details</p>
                <h2 class="text-2xl font-bold text-white">{{ $lesson->title }}</h2>
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
                    <div class="relative">
                        <img src="{{ asset('storage/'.$lesson->thumbnail_path) }}" alt="{{ $lesson->title }}" class="h-80 w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-transparent to-transparent"></div>
                    </div>
                @else
                    <div class="flex h-80 items-center justify-center bg-gradient-to-br from-[#1a1a1a] to-[#E50914]/30 text-6xl font-black text-white">{{ strtoupper(substr($lesson->title, 0, 1)) }}</div>
                @endif

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-3 text-xs uppercase tracking-[0.3em] text-white/25">
                        <span>{{ $lesson->content_type }}</span>
                        <span class="h-1 w-1 rounded-full bg-white/20"></span>
                        <span>{{ $lesson->tutor->name ?? 'Tutor' }}</span>
                    </div>
                    <div class="prose prose-invert mt-4 max-w-none text-base leading-7 text-white/50">
                        {!! $lesson->description !!}
                    </div>

                    <div class="gradient-line mt-6"></div>

                    <div class="mt-6 rounded-3xl bg-white/[0.03] border border-white/10 p-5">
                        <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-white/30">Content</h3>
                        @if ($lesson->video_url)
                            <a href="{{ $lesson->video_url }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                Open video link
                            </a>
                        @elseif ($lesson->video_path)
                            <a href="{{ asset('storage/'.$lesson->video_path) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                Open uploaded video
                            </a>
                        @elseif ($lesson->attachment_paths)
                            <div class="mt-3 space-y-2">
                                @foreach ($lesson->attachment_paths as $attachment)
                                    <a href="{{ asset('storage/'.$attachment) }}" target="_blank" class="flex items-center gap-2 text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        {{ basename($attachment) }}
                                    </a>
                                @endforeach
                            </div>
                        @elseif ($lesson->attachment_path)
                            <a href="{{ asset('storage/'.$lesson->attachment_path) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                Download file
                            </a>
                        @else
                            <p class="mt-3 whitespace-pre-line text-white/50">{{ $lesson->content ?: 'This lesson currently has no additional content block.' }}</p>
                        @endif
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                {{-- Quiz card --}}
                <div class="lms-card p-6">
                    <h3 class="text-lg font-bold text-white">Quiz</h3>
                    @if ($lesson->quiz)
                        <p class="mt-2 text-sm text-white/40">{{ $lesson->quiz->questions->count() }} questions, passing score {{ $lesson->quiz->passing_score }}%</p>
                        <p class="mt-2 text-xs uppercase tracking-[0.2em] {{ $lesson->quiz->is_published ? 'text-emerald-300' : 'text-amber-200' }}">
                            {{ $lesson->quiz->is_published ? 'Live quiz' : 'Draft quiz' }}
                        </p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                                <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="lms-button">Preview / Attempt</a>
                                <a href="{{ route('quizzes.edit', $lesson->quiz) }}" class="lms-button-secondary">Edit quiz</a>
                            @elseif (! $lesson->quiz->is_published)
                                <span class="rounded-full border border-amber-300/30 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-200">Quiz will be available when published.</span>
                            @elseif ($isEnrolled)
                                <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="lms-button">Attempt quiz</a>
                            @else
                                <form method="POST" action="{{ route('lessons.enroll', $lesson) }}">
                                    @csrf
                                    <button type="submit" class="lms-button">Enroll to unlock quiz</button>
                                </form>
                            @endif
                        </div>
                    @else
                        <p class="mt-2 text-sm text-white/40">No quiz has been attached to this lesson yet.</p>
                        @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                            <div class="mt-5">
                                <a href="{{ route('lessons.quizzes.create', $lesson) }}" class="lms-button">Create quiz</a>
                            </div>
                        @endif
                    @endif
                </div>

                @if ($lesson->live_class_provider || $lesson->live_class_meeting_url || $lesson->live_class_start_at)
                    <div class="lms-card p-6">
                        <h3 class="text-lg font-bold text-white">Live class</h3>
                        <p class="mt-2 text-sm text-white/40">{{ ucfirst(str_replace('_', ' ', $lesson->live_class_provider ?? 'Live class')) }}</p>
                        @if ($lesson->live_class_title)
                            <p class="mt-3 text-sm font-semibold text-white">{{ $lesson->live_class_title }}</p>
                        @endif

                        <dl class="mt-4 space-y-3 text-sm">
                            @if ($lesson->live_class_start_at)
                                <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                                    <dt class="text-white/30">Starts</dt>
                                    <dd class="font-semibold text-white">{{ $lesson->live_class_start_at->format('M j, Y g:i A') }}</dd>
                                </div>
                            @endif
                            @if ($lesson->live_class_duration)
                                <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                                    <dt class="text-white/30">Duration</dt>
                                    <dd class="font-semibold text-white">{{ $lesson->live_class_duration }} min</dd>
                                </div>
                            @endif
                            @if ($lesson->live_class_meeting_code)
                                <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                                    <dt class="text-white/30">Meeting code</dt>
                                    <dd class="font-semibold text-white">{{ $lesson->live_class_meeting_code }}</dd>
                                </div>
                            @endif
                            @if ($lesson->live_class_passcode)
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-white/30">Passcode</dt>
                                    <dd class="font-semibold text-white">{{ $lesson->live_class_passcode }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($lesson->live_class_meeting_url)
                            <div class="mt-5">
                                <a href="{{ $lesson->live_class_meeting_url }}" target="_blank" class="lms-button w-full">
                                    Join {{ ucfirst(str_replace('_', ' ', $lesson->live_class_provider ?? 'class')) }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Metadata card --}}
                <div class="lms-card p-6">
                    <h3 class="text-lg font-bold text-white">Lesson metadata</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between border-b border-white/5 pb-3">
                            <dt class="text-white/30">Author</dt>
                            <dd class="font-semibold text-white">{{ $lesson->tutor->name ?? 'Tutor' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/5 pb-3">
                            <dt class="text-white/30">Created</dt>
                            <dd class="font-semibold text-white">{{ $lesson->created_at?->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-white/30">Updated</dt>
                            <dd class="font-semibold text-white">{{ $lesson->updated_at?->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>
