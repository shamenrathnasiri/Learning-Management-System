<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quizzes</p>
                <h2 class="text-3xl font-bold text-white">Quiz creation hub</h2>
            </div>
            <p class="max-w-2xl text-sm text-white/40">Select a lesson to build a quiz, then manage it from one clean dashboard.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="grid gap-6 md:grid-cols-3">
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Lessons</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['lessons'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Available lessons</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Quizzes</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['quizzes'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Recent quizzes</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Ready to build</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['ready'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Lessons without quizzes</p>
            </div>
        </section>

        <section class="lms-card p-6 md:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">Create a quiz from a lesson</h3>
                    <p class="mt-1 text-sm text-white/35">Pick a lesson below to open the quiz builder.</p>
                </div>
            </div>

            <div class="gradient-line mt-6"></div>

            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($lessons as $lesson)
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#E50914]/30 hover:bg-[#E50914]/5">
                        <div class="flex items-center justify-between gap-4 text-xs uppercase tracking-[0.25em] text-white/25">
                            <span>{{ $lesson->module ?? 'Lesson' }}</span>
                            <span class="rounded-full px-2 py-1 {{ $lesson->quiz ? 'bg-emerald-500/10 text-emerald-300' : 'bg-[#E50914]/10 text-[#ff8088]' }}">
                                {{ $lesson->quiz ? 'Has quiz' : 'Ready' }}
                            </span>
                        </div>
                        <h4 class="mt-4 text-lg font-bold text-white">{{ $lesson->title }}</h4>
                        <p class="mt-2 text-sm leading-6 text-white/40">{{ $lesson->tutor->name ?? 'Tutor' }}</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('lessons.quizzes.create', $lesson) }}" class="lms-button">Build quiz</a>
                            @if ($lesson->quiz)
                                <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="lms-button-secondary">View quiz</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-white/10 bg-white/[0.02] p-6 text-sm text-white/30 md:col-span-2 xl:col-span-3">
                        No lessons are available for quiz creation yet.
                    </div>
                @endforelse
            </div>
        </section>

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
                            <span>{{ $quiz->lesson->title ?? 'Lesson' }}</span>
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
