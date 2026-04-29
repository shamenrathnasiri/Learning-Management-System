<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quiz</p>
                <h2 class="text-2xl font-bold text-white">{{ $quiz->title }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('lessons.show', $quiz->lesson) }}" class="lms-button-secondary">Back to lesson</a>
                @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="lms-button">Edit quiz</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('quizzes.attempt.store', $quiz) }}" class="lms-card space-y-6 p-6">
            @csrf

            {{-- Quiz info header --}}
            <div class="rounded-3xl border border-[#E50914]/20 bg-[#E50914]/10 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-[#E50914]/60">Lesson</p>
                <h3 class="mt-3 text-2xl font-bold text-white">{{ $quiz->lesson->title }}</h3>
                <p class="mt-2 text-sm text-white/50">Passing score: {{ $quiz->passing_score }}%</p>
                @if ($quiz->description)
                    <p class="mt-4 text-sm text-white/60">{{ $quiz->description }}</p>
                @endif
            </div>

            {{-- Questions --}}
            @foreach ($quiz->questions as $question)
                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition hover:border-white/20">
                    <h4 class="text-lg font-semibold text-white">{{ $question->question }}</h4>
                    <div class="mt-4 space-y-3 text-sm">
                        @foreach ([
                            0 => $question->option_one,
                            1 => $question->option_two,
                            2 => $question->option_three,
                            3 => $question->option_four,
                        ] as $index => $option)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.02] px-4 py-3 text-white/70 transition-all duration-200 hover:border-[#E50914]/40 hover:bg-[#E50914]/5 hover:text-white has-[:checked]:border-[#E50914] has-[:checked]:bg-[#E50914]/10 has-[:checked]:text-white">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $index }}" class="border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" required>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="gradient-line"></div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('lessons.show', $quiz->lesson) }}" class="lms-button-secondary">Cancel</a>
                <button class="lms-button" type="submit">Submit quiz</button>
            </div>
        </form>
    </div>
</x-app-layout>
