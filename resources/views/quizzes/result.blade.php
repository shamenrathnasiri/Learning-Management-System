<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Result</p>
            <h2 class="text-2xl font-bold text-white">{{ $quizAttempt->quiz->title }}</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        {{-- Score summary --}}
        <section class="lms-card p-8">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 items-start">
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Student</p>
                    <p class="mt-3 text-xl font-bold text-white">{{ $quizAttempt->student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Score</p>
                    <p class="mt-3 text-3xl font-black text-white">{{ $quizAttempt->score }}<span class="text-lg text-white/30">/{{ $quizAttempt->total_questions }}</span></p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Percentage</p>
                    <p class="mt-3 text-3xl font-black text-[#E50914]">{{ $quizAttempt->percentage }}%</p>
                </div>
                <div class="lg:col-span-1">
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Status</p>
                    <div class="mt-3">
                        @if($quizAttempt->percentage >= 70)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Passed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E50914]/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#E50914]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                Failed
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="mt-8 pt-6 border-t border-white/5">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em] mb-3">Progress</p>
                <div class="relative w-full h-3 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute h-full bg-gradient-to-r from-[#E50914] to-[#ff4d55] rounded-full transition-all duration-700 shadow-lg shadow-[#E50914]/30" style="width: {{ $quizAttempt->percentage }}%"></div>
                </div>
                <p class="mt-2 text-xs text-white/30">{{ $quizAttempt->percentage }}% Complete</p>
            </div>
        </section>

        {{-- Question review --}}
        <section class="lms-card p-8">
            <h3 class="text-2xl font-bold text-white mb-2">Question Review</h3>
            <p class="text-sm text-white/40 mb-6">{{ $quizAttempt->answers->where('is_correct', true)->count() }} correct out of {{ $quizAttempt->total_questions }} questions</p>
            <div class="space-y-4">
                @foreach ($quizAttempt->answers as $idx => $answer)
                    <div class="rounded-2xl border-2 {{ $answer->is_correct ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-[#E50914]/30 bg-[#E50914]/5' }} p-6 transition hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full font-bold text-xs text-white {{ $answer->is_correct ? 'bg-emerald-500' : 'bg-[#E50914]' }} shadow-lg">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-xs font-bold uppercase tracking-wider {{ $answer->is_correct ? 'text-emerald-400' : 'text-[#E50914]' }}">
                                        {{ $answer->is_correct ? '✓ Correct' : '✗ Incorrect' }}
                                    </span>
                                </div>
                                <p class="font-semibold text-white text-lg mt-3">{{ $answer->question->question }}</p>
                                <p class="mt-3 text-sm text-white/50">Your answer: <span class="font-bold text-white/80">{{ ['A','B','C','D'][$answer->selected_option] ?? 'N/A' }}</span></p>
                                @if(!$answer->is_correct)
                                    <p class="mt-2 text-sm text-white/50">Correct answer: <span class="font-bold text-emerald-400">{{ ['A','B','C','D'][$answer->question->correct_option] ?? 'N/A' }}</span></p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
