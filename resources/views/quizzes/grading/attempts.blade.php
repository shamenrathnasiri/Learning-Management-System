<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Grading</p>
                <h2 class="text-2xl font-bold text-white">{{ $quiz->title }}</h2>
                <p class="mt-1 text-sm text-white/50">Review and grade student submissions</p>
            </div>
            <a href="{{ route('quizzes.edit', $quiz) }}" class="lms-button-secondary">Back to quiz</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- Statistics --}}
        <div class="grid gap-4 md:grid-cols-3 mb-8">
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Total Submissions</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $attempts->total() }}</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Pending Grades</p>
                <p class="mt-3 text-3xl font-bold text-amber-400">{{ $attempts->sum('pending_grades') }}</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Completed</p>
                <p class="mt-3 text-3xl font-bold text-emerald-400">{{ $attempts->sum('graded_count') }}</p>
            </div>
        </div>

        {{-- Attempts table --}}
        <div class="lms-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/[0.02]">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Score</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Pending</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Submitted</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-white/40"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($attempts as $attempt)
                            <tr class="transition hover:bg-white/[0.02]">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">{{ $attempt->student->name }}</p>
                                    <p class="text-xs text-white/40">{{ $attempt->student->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-white">{{ $attempt->score }}</span>
                                        <span class="text-sm text-white/40">/{{ $quiz->questions->sum('marks') }}</span>
                                    </div>
                                    <p class="text-xs text-white/40 mt-1">{{ $attempt->percentage }}%</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($attempt->pending_grades > 0)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Graded
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($attempt->pending_grades > 0)
                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-amber-500/20 text-xs font-bold text-amber-300">{{ $attempt->pending_grades }}</span>
                                    @else
                                        <span class="text-white/40">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-white/50">
                                    {{ $attempt->completed_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('grading.show', $attempt) }}" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-sm font-semibold text-white/70 transition hover:border-[#E50914]/30 hover:bg-[#E50914]/5 hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-white/40">No submissions yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $attempts->links() }}
        </div>
    </div>
</x-app-layout>
