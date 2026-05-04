<x-app-layout>
    @php
        $isPrivilegedViewer = auth()->user()->isTutor() || auth()->user()->isAdministrator();
        $canShowCorrectAnswers = $isPrivilegedViewer || $quizAttempt->quiz->show_correct_answers;
        $canShowExplanations = $isPrivilegedViewer || $quizAttempt->quiz->show_explanations;
    @endphp

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
                        @if($quizAttempt->percentage >= ($quizAttempt->quiz->passing_score ?? 70))
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
            <p class="text-sm text-white/40 mb-6">{{ $quizAttempt->answers->filter(fn($a) => $a->is_correct)->count() }} correct out of {{ $quizAttempt->total_questions }} questions</p>
            <div class="space-y-4">
                @foreach ($quizAttempt->answers as $idx => $answer)
                    @php
                        $isSubjective = in_array($answer->question->type, ['essay', 'short_answer']);
                        $isPending = $isSubjective && $answer->status === 'pending';
                        $borderClass = $isPending ? 'border-amber-500/30 bg-amber-500/5' : ($answer->is_correct ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-[#E50914]/30 bg-[#E50914]/5');
                    @endphp
                    <div class="rounded-2xl border-2 {{ $borderClass }} p-6 transition hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    @if ($isPending)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full font-bold text-xs text-white bg-amber-500 shadow-lg">⏱</span>
                                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Pending Grade</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full font-bold text-xs text-white {{ $answer->is_correct ? 'bg-emerald-500' : 'bg-[#E50914]' }} shadow-lg">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="text-xs font-bold uppercase tracking-wider {{ $answer->is_correct ? 'text-emerald-400' : 'text-[#E50914]' }}">
                                            {{ $answer->is_correct ? '✓ Correct' : '✗ Incorrect' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="font-semibold text-white text-lg mt-3">{{ $answer->question->question }}</p>

                                {{-- Question type badge --}}
                                <div class="mt-2">
                                    <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-2 py-1 text-xs text-white/60 font-medium">
                                        {{ ucwords(str_replace('_', ' ', $answer->question->type)) }}
                                    </span>
                                </div>

                                {{-- Student's answer --}}
                                @if ($isSubjective && !$isPending)
                                    <p class="mt-3 text-sm text-white/50">Your answer:</p>
                                    <p class="mt-2 p-3 rounded border border-white/10 bg-black/20 text-sm text-white/80 whitespace-pre-wrap">{{ $answer->text_answer ?: 'No answer provided' }}</p>
                                @elseif (!$isSubjective)
                                    <p class="mt-3 text-sm text-white/50">Your answer: <span class="font-bold text-white/80">{{ ['A','B','C','D'][$answer->selected_option] ?? 'N/A' }}</span></p>
                                    @if(!$answer->is_correct && $canShowCorrectAnswers)
                                        <p class="mt-2 text-sm text-white/50">Correct answer: <span class="font-bold text-emerald-400">{{ ['A','B','C','D'][$answer->question->correct_option] ?? 'N/A' }}</span></p>
                                    @endif
                                @endif

                                {{-- Points display --}}
                                @if ($answer->marks_obtained !== null)
                                    <div class="mt-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1">
                                        <span class="text-xs text-white/50">Points:</span>
                                        <span class="text-sm font-bold text-white">{{ $answer->marks_obtained }} / {{ $answer->question->marks }}</span>
                                    </div>
                                @endif

                                {{-- Explanation --}}
                                @if ($canShowExplanations && $answer->question->explanation)
                                    <p class="mt-3 text-sm text-white/50">Explanation: <span class="font-medium text-white/80">{{ $answer->question->explanation }}</span></p>
                                @endif

                                {{-- Tutor feedback --}}
                                @if ($answer->tutor_feedback && ($isPrivilegedViewer || !$isPending))
                                    <div class="mt-4 rounded-lg border-l-4 border-[#E50914] bg-[#E50914]/10 p-4">
                                        <p class="text-xs font-semibold text-white/60 uppercase tracking-[0.1em] mb-1">Tutor Feedback</p>
                                        <p class="text-sm text-white/80">{{ $answer->tutor_feedback }}</p>
                                        @if ($answer->graded_at && $answer->gradedByUser)
                                            <p class="mt-2 text-xs text-white/40">— {{ $answer->gradedByUser->name }}, {{ $answer->graded_at->format('M j, Y') }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Pending badge --}}
                                @if ($isPending && !$isPrivilegedViewer)
                                    <div class="mt-4 rounded-lg border border-amber-500/30 bg-amber-500/10 p-4">
                                        <p class="text-sm font-semibold text-amber-300">This answer is pending tutor grading. Check back later for feedback and your score.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
