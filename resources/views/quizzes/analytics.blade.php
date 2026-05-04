<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Tutor analytics</p>
                <h2 class="text-3xl font-extrabold tracking-tight text-white">Quiz performance dashboard</h2>
                <p class="mt-2 max-w-3xl text-sm text-white/40">Monitor student scores, average results, pass and fail trends, and question-wise performance across your quizzes.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('quizzes.create') }}" class="lms-button">Create quiz</a>
                <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Browse lessons</a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Quizzes</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ $summary['quizzes'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Managed</p>
            </div>
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Attempts</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ $summary['attempts'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Student submissions</p>
            </div>
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Average score</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ number_format($summary['average_percentage'], 1) }}%</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Across graded attempts</p>
            </div>
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Pass rate</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ number_format($summary['pass_rate'], 1) }}%</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-emerald-400">Above passing score</p>
            </div>
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Fail rate</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ number_format($summary['fail_rate'], 1) }}%</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-amber-300">Below passing score</p>
            </div>
            <div class="lms-card p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Pending grading</p>
                <p class="mt-3 text-3xl font-black tracking-tight text-white">{{ $summary['pending_attempts'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-amber-300">Needs tutor review</p>
            </div>
        </section>

        <section class="lms-card overflow-hidden p-6 md:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">Recent student results</h3>
                    <p class="mt-1 text-sm text-white/35">Latest quiz submissions across your dashboard.</p>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                <table class="lms-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentAttempts as $attempt)
                            @php
                                $isComplete = $attempt->answers->every(fn ($answer) => $answer->isGraded());
                                $passed = $isComplete && $attempt->percentage >= ($attempt->quiz?->passing_score ?? 0);
                            @endphp
                            <tr>
                                <td class="font-medium text-white">{{ $attempt->student->name ?? 'Unknown student' }}</td>
                                <td class="text-white/50">{{ $attempt->quiz->title ?? 'Quiz' }}</td>
                                <td class="font-semibold text-white">{{ $attempt->score }}/{{ $attempt->quiz->total_marks ?? $attempt->total_questions }}</td>
                                <td>
                                    @if (! $isComplete)
                                        <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">Pending</span>
                                    @elseif ($passed)
                                        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Pass</span>
                                    @else
                                        <span class="rounded-full bg-[#E50914]/10 px-3 py-1 text-xs font-semibold text-[#ff9ea3]">Fail</span>
                                    @endif
                                </td>
                                <td class="text-white/30">{{ optional($attempt->completed_at ?? $attempt->created_at)->format('M j, Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-white/30">No quiz attempts have been submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @forelse ($quizInsights as $insight)
            @php
                $quiz = $insight['quiz'];
                $metrics = $insight['metrics'];
            @endphp
            <section class="lms-card space-y-6 p-6 md:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/30">
                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1">{{ $quiz->lesson->title ?? 'Unassigned quiz' }}</span>
                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1">{{ $quiz->questions->count() }} questions</span>
                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-3 py-1">Passing {{ $quiz->passing_score }}%</span>
                            @if ($quiz->is_published)
                                <span class="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 text-emerald-300">Live</span>
                            @else
                                <span class="rounded-full border border-amber-500/20 bg-amber-500/10 px-3 py-1 text-amber-300">Draft</span>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ $quiz->title }}</h3>
                        @if ($quiz->description)
                            <p class="max-w-3xl text-sm leading-6 text-white/40">{{ $quiz->description }}</p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($metrics['attempts'])
                            <a href="{{ route('grading.index-attempts', $quiz) }}" class="lms-button">Grade responses</a>
                        @endif
                        <a href="{{ route('quizzes.edit', $quiz) }}" class="lms-button-secondary">Edit</a>
                        <form method="POST" action="{{ route('quizzes.duplicate', $quiz) }}">
                            @csrf
                            <button type="submit" class="lms-button-secondary">Duplicate</button>
                        </form>
                        <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}" onsubmit="return confirm('Delete this quiz? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-4 py-2 text-sm font-semibold text-[#ff9ea3] transition hover:border-[#E50914]/40 hover:bg-[#E50914]/20 hover:text-white">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/30">Attempts</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ $metrics['attempts'] }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/30">Average score</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format($metrics['average_percentage'], 1) }}%</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/30">Pass rate</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format($metrics['pass_rate'], 1) }}%</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/30">Fail rate</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format($metrics['fail_rate'], 1) }}%</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/30">Pending</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ $metrics['pending_attempts'] }}</p>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-black/20 p-4 md:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-white">Recent student scores</h4>
                            <p class="mt-1 text-sm text-white/35">Latest attempts for this quiz.</p>
                        </div>
                    </div>

                    <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
                        <table class="lms-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($insight['recent_attempts'] as $attempt)
                                    @php
                                        $isComplete = $attempt->answers->every(fn ($answer) => $answer->isGraded());
                                        $passed = $isComplete && $attempt->percentage >= $quiz->passing_score;
                                    @endphp
                                    <tr>
                                        <td class="font-medium text-white">{{ $attempt->student->name ?? 'Unknown student' }}</td>
                                        <td class="text-white/70">{{ $attempt->score }}/{{ $quiz->total_marks ?? $attempt->total_questions }}</td>
                                        <td class="text-white/70">{{ number_format((float) $attempt->percentage, 1) }}%</td>
                                        <td>
                                            @if (! $isComplete)
                                                <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">Pending</span>
                                            @elseif ($passed)
                                                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Pass</span>
                                            @else
                                                <span class="rounded-full bg-[#E50914]/10 px-3 py-1 text-xs font-semibold text-[#ff9ea3]">Fail</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-white/30">No attempts yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <details class="rounded-3xl border border-white/10 bg-white/[0.02] p-4 md:p-6">
                    <summary class="cursor-pointer list-none text-lg font-bold text-white">
                        Question-wise analysis
                        <span class="ml-2 text-sm font-normal text-white/35">{{ $quiz->questions->count() }} items</span>
                    </summary>
                    <div class="mt-6 space-y-4">
                        @forelse ($insight['questions'] as $questionInsight)
                            @php
                                $question = $questionInsight['question'];
                                $isObjective = in_array($question->type, ['multiple_choice', 'true_false'], true);
                            @endphp
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 md:p-5">
                                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.25em] text-white/30">
                                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-2 py-1">{{ ucwords(str_replace('_', ' ', $question->type)) }}</span>
                                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-2 py-1">{{ $questionInsight['responses'] }} responses</span>
                                            <span class="rounded-full border border-white/10 bg-white/[0.03] px-2 py-1">{{ $question->marks }} marks</span>
                                        </div>
                                        <p class="mt-3 text-base font-semibold text-white">{{ $question->question }}</p>
                                    </div>
                                    <span class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-xs font-semibold text-[#ff9ea3]">
                                        {{ $isObjective ? $questionInsight['correct_responses'].' correct' : $questionInsight['average_marks'].' avg marks' }}
                                    </span>
                                </div>

                                <div class="mt-4 h-2 overflow-hidden rounded-full bg-white/5">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#E50914] to-emerald-500" style="width: {{ min(100, (float) $questionInsight['performance_rate']) }}%"></div>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-3 text-sm text-white/45">
                                    <div class="rounded-2xl bg-white/[0.03] px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/30">Average marks</p>
                                        <p class="mt-1 font-semibold text-white">{{ number_format((float) $questionInsight['average_marks'], 2) }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/[0.03] px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/30">Performance</p>
                                        <p class="mt-1 font-semibold text-white">{{ number_format((float) $questionInsight['performance_rate'], 1) }}%</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/[0.03] px-4 py-3">
                                        <p class="text-xs uppercase tracking-[0.2em] text-white/30">Responses</p>
                                        <p class="mt-1 font-semibold text-white">{{ $questionInsight['responses'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/10 bg-white/[0.02] p-6 text-sm text-white/30">
                                No questions are attached to this quiz yet.
                            </div>
                        @endforelse
                    </div>
                </details>
            </section>
        @empty
            <section class="lms-card border border-dashed border-white/10 p-10 text-center">
                <h3 class="text-2xl font-bold text-white">No quizzes to analyze yet</h3>
                <p class="mt-3 text-sm text-white/40">Create a quiz to start tracking student performance, pass rates, and question-level insights.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('quizzes.create') }}" class="lms-button">Create quiz</a>
                    <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Browse lessons</a>
                </div>
            </section>
        @endforelse
    </div>
</x-app-layout>
