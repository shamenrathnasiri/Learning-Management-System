<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quizzes</p>
                <h2 class="text-3xl font-bold text-white">Create quiz</h2>
            </div>
            <p class="max-w-2xl text-sm text-white/40">Build a modern quiz with clear assignment, timing, and scoring settings.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.store') }}" class="lms-card space-y-8 p-6 md:p-8">
            @csrf

            @if ($errors->any())
                <div class="rounded-3xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-100">
                    <p class="font-semibold">Please fix the highlighted fields.</p>
                </div>
            @endif

            @include('quizzes._form', ['quiz' => null, 'courses' => $courses, 'lessons' => $lessons])

            <div class="gradient-line"></div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Cancel</a>
                <button type="submit" class="lms-button">Create quiz</button>
            </div>
        </form>

        <section class="lms-card p-6 md:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">Recent quizzes</h3>
                    <p class="mt-1 text-sm text-white/35">Return to existing quizzes for review or edits.</p>
                </div>
            </div>

            <div class="gradient-line mt-6"></div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @forelse ($recentQuizzes as $quiz)
                    <a href="{{ route('quizzes.show', $quiz) }}" class="rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#E50914]/30 hover:bg-[#E50914]/5">
                        <div class="flex items-center justify-between gap-4 text-xs uppercase tracking-[0.25em] text-white/25">
                            <span>{{ $quiz->lesson->title ?? ($quiz->course_id ? 'Course '.$quiz->course_id : 'Quiz') }}</span>
                            <span class="text-[#E50914]">{{ $quiz->questions->count() }} questions</span>
                        </div>
                        <h4 class="mt-4 text-lg font-bold text-white">{{ $quiz->title }}</h4>
                        <p class="mt-2 text-sm leading-6 text-white/40">Passing score {{ $quiz->passing_score }}%</p>
                    </a>
                @empty
                    <div class="rounded-3xl border border-dashed border-white/10 bg-white/[0.02] p-6 text-sm text-white/30 md:col-span-2">
                        No quizzes have been published yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
