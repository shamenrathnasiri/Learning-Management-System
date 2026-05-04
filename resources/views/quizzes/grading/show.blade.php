<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Grading</p>
                <h2 class="text-2xl font-bold text-white">{{ $attempt->quiz->title }}</h2>
                <p class="mt-1 text-sm text-white/50">Grading submission by {{ $attempt->student->name }}</p>
            </div>
            <a href="{{ route('grading.index-attempts', $attempt->quiz) }}" class="lms-button-secondary">Back to attempts</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        {{-- Score summary --}}
        <section class="lms-card p-8">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 items-start">
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Student</p>
                    <p class="mt-3 text-xl font-bold text-white">{{ $attempt->student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Current Score</p>
                    <p class="mt-3 text-3xl font-black text-white">{{ $attempt->score }}<span class="text-lg text-white/30">/{{ $attempt->quiz->questions->sum('marks') }}</span></p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Percentage</p>
                    <p class="mt-3 text-3xl font-black" :class="{
                        'text-[#E50914]': {{ $attempt->percentage }} < {{ $attempt->quiz->passing_score ?? 70 }},
                        'text-emerald-400': {{ $attempt->percentage }} >= {{ $attempt->quiz->passing_score ?? 70 }}
                    }">
                        {{ $attempt->percentage }}%
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em]">Grading Status</p>
                    <div class="mt-3">
                        @if ($pending_subjective > 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-400">
                                {{ $pending_subjective }} pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-bold text-emerald-400">
                                ✓ All graded
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="mt-8 pt-6 border-t border-white/5">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-[0.2em] mb-3">Progress</p>
                <div class="relative w-full h-3 bg-white/5 rounded-full overflow-hidden">
                    <div class="absolute h-full bg-gradient-to-r from-[#E50914] to-[#ff4d55] rounded-full transition-all duration-700 shadow-lg shadow-[#E50914]/30" style="width: {{ $attempt->percentage }}%"></div>
                </div>
                <p class="mt-2 text-xs text-white/30">{{ $attempt->percentage }}% Complete</p>
            </div>
        </section>

        {{-- Objective questions (auto-graded) --}}
        @if ($objective_answers->count() > 0)
            <section class="lms-card p-8">
                <h3 class="text-xl font-bold text-white mb-2">Objective Questions (Auto-graded)</h3>
                <p class="text-sm text-white/40 mb-6">Score: {{ $objective_score }} / {{ $objective_answers->sum(fn($a) => $a->question->marks) }} points</p>
                <div class="space-y-4">
                    @foreach ($objective_answers as $answer)
                        <div class="rounded-2xl border-2 {{ $answer->is_correct ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-[#E50914]/30 bg-[#E50914]/5' }} p-6 transition hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full font-bold text-xs text-white {{ $answer->is_correct ? 'bg-emerald-500' : 'bg-[#E50914]' }} shadow-lg">
                                            {{ $answer->is_correct ? '✓' : '✗' }}
                                        </span>
                                        <span class="text-xs font-bold uppercase tracking-wider {{ $answer->is_correct ? 'text-emerald-400' : 'text-[#E50914]' }}">
                                            {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                                        </span>
                                    </div>
                                    <p class="font-semibold text-white text-lg mt-3">{{ $answer->question->question }}</p>
                                    <p class="mt-3 text-sm text-white/50">
                                        Your answer: <span class="font-bold text-white/80">{{ ['A','B','C','D'][$answer->selected_option] ?? 'Not answered' }}</span>
                                        ({{ $answer->marks_obtained }} / {{ $answer->question->marks }} points)
                                    </p>
                                    @if(!$answer->is_correct)
                                        <p class="mt-2 text-sm text-white/50">Correct answer: <span class="font-bold text-emerald-400">{{ ['A','B','C','D'][$answer->question->correct_option] ?? 'N/A' }}</span></p>
                                    @endif
                                    @if ($answer->question->explanation)
                                        <p class="mt-2 text-sm text-white/50">Explanation: <span class="font-medium text-white/80">{{ $answer->question->explanation }}</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Subjective questions (needs grading) --}}
        @if ($subjective_answers->count() > 0)
            <form method="POST" action="{{ route('grading.bulk-grade', $attempt) }}" class="space-y-6">
                @csrf
                <section class="lms-card p-8">
                    <h3 class="text-xl font-bold text-white mb-2">Subjective Answers ({{ $pending_subjective }} pending)</h3>
                    <p class="text-sm text-white/40 mb-6">These answers require manual grading. Current score: {{ $subjective_score }} / {{ $subjective_answers->sum(fn($a) => $a->question->marks) }} points</p>

                    <div class="space-y-6">
                        @foreach ($subjective_answers as $index => $answer)
                            <div class="rounded-2xl border-2 {{ $answer->status === 'graded' ? 'border-emerald-500/30 bg-emerald-500/5' : 'border-amber-500/30 bg-amber-500/5' }} p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full font-bold text-xs text-white {{ $answer->status === 'graded' ? 'bg-emerald-500' : 'bg-amber-500' }} shadow-lg">
                                                {{ $index + 1 }}
                                            </span>
                                            <h4 class="font-semibold text-white">{{ $answer->question->question }}</h4>
                                        </div>
                                    </div>
                                    <div class="text-right text-xs">
                                        @if ($answer->status === 'graded')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/20 px-2 py-1 text-xs text-emerald-300">
                                                ✓ Graded
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/20 px-2 py-1 text-xs text-amber-300">
                                                ⏱ Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Student's answer --}}
                                <div class="mb-6 rounded-xl border border-white/10 bg-black/20 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40 mb-2">Student's Answer</p>
                                    @if ($answer->text_answer)
                                        <p class="text-sm text-white/80 whitespace-pre-wrap">{{ $answer->text_answer }}</p>
                                    @else
                                        <p class="text-sm text-white/50 italic">No answer provided</p>
                                    @endif
                                </div>

                                {{-- Grading form --}}
                                <div class="space-y-4">
                                    <div>
                                        <label for="grades[{{ $answer->id }}][marks_obtained]" class="block text-sm font-semibold text-white mb-2">
                                            Points
                                            <span class="text-white/40">(Max: {{ $answer->question->marks }})</span>
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="number"
                                                id="grades[{{ $answer->id }}][marks_obtained]"
                                                name="grades[{{ $answer->id }}][marks_obtained]"
                                                value="{{ $answer->marks_obtained ?? '' }}"
                                                min="0"
                                                max="{{ $answer->question->marks }}"
                                                @if($answer->status === 'graded') readonly @endif
                                                class="flex-1 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-white placeholder-white/30 focus:border-[#E50914] focus:outline-none focus:ring-1 focus:ring-[#E50914]"
                                                placeholder="0"
                                            >
                                            <span class="text-white/40 font-semibold">/ {{ $answer->question->marks }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="grades[{{ $answer->id }}][tutor_feedback]" class="block text-sm font-semibold text-white mb-2">
                                            Feedback
                                        </label>
                                        <textarea
                                            id="grades[{{ $answer->id }}][tutor_feedback]"
                                            name="grades[{{ $answer->id }}][tutor_feedback]"
                                            @if($answer->status === 'graded') readonly @endif
                                            class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-white placeholder-white/30 focus:border-[#E50914] focus:outline-none focus:ring-1 focus:ring-[#E50914]"
                                            rows="3"
                                            placeholder="Provide constructive feedback for the student..."
                                        >{{ $answer->tutor_feedback }}</textarea>
                                    </div>

                                    @if ($answer->graded_at && $answer->gradedByUser)
                                        <div class="text-xs text-white/40 pt-2 border-t border-white/10">
                                            <p>Graded by <span class="font-semibold">{{ $answer->gradedByUser->name }}</span> on {{ $answer->graded_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t border-white/10 pt-6">
                        <a href="{{ route('grading.index-attempts', $attempt->quiz) }}" class="lms-button-secondary">Cancel</a>
                        <button type="submit" class="lms-button">
                            <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save grades
                        </button>
                    </div>
                </section>
            </form>
        @endif

        {{-- No subjective questions --}}
        @if ($subjective_answers->count() === 0)
            <section class="lms-card p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="mt-4 text-white/40">This quiz contains no subjective questions requiring manual grading.</p>
                <p class="mt-2 text-sm text-white/30">All answers have been automatically scored.</p>
            </section>
        @endif
    </div>
</x-app-layout>
