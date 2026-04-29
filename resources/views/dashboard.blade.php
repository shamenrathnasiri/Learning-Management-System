<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Role dashboard</p>
                <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ ucfirst($user->role) }} overview</h2>
            </div>
            <p class="max-w-2xl text-sm text-white/40">Track lessons, quizzes, and attempts with a clear layout and fast actions tailored to your role.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        {{-- ── Stat Cards ── --}}
        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="lms-card group p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Students</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['students'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Active users</p>
                <div class="mt-4 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
            </div>
            <div class="lms-card group p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Tutors</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['tutors'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Content creators</p>
                <div class="mt-4 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
            </div>
            <div class="lms-card group p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Lessons</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['lessons'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Published modules</p>
                <div class="mt-4 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
            </div>
            <div class="lms-card group p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/30">Quiz attempts</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-white">{{ $stats['attempts'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Tracked results</p>
                <div class="mt-4 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
            </div>
        </section>

        {{-- ── Main Content Grid ── --}}
        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
            {{-- Recent lessons --}}
            <section class="lms-card p-6 md:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Recent lessons</h3>
                        <p class="mt-1 text-sm text-white/35">Latest content added to the platform.</p>
                    </div>
                    <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Browse lessons</a>
                </div>

                <div class="gradient-line mt-6"></div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @forelse ($recentLessons as $lesson)
                        <a href="{{ route('lessons.show', $lesson) }}" class="group rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#E50914]/30 hover:bg-[#E50914]/5 hover:shadow-lg hover:shadow-[#E50914]/5">
                            <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-white/25">
                                <span>{{ $lesson->content_type }}</span>
                                <span class="text-[#E50914]">{{ $lesson->tutor->name ?? 'Tutor' }}</span>
                            </div>
                            <h4 class="mt-4 text-lg font-bold text-white group-hover:text-[#E50914] transition">{{ $lesson->title }}</h4>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-white/40">{{ $lesson->description }}</p>
                        </a>
                    @empty
                        <div class="rounded-3xl border border-dashed border-white/10 bg-white/[0.02] p-6 text-sm text-white/30">
                            No lessons have been created yet.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if ($user->isTutor() || $user->isAdministrator())
                        <a href="{{ route('lessons.create') }}" class="lms-button">Create lesson</a>
                    @endif
                    @if ($user->isAdministrator())
                        <a href="{{ route('admin.users.index') }}" class="lms-button-secondary">Manage users</a>
                    @endif
                </div>
            </section>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- System summary --}}
                <div class="lms-card p-6 md:p-8">
                    <h3 class="text-xl font-bold text-white">System summary</h3>
                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-2xl bg-white/[0.04] px-4 py-3">
                            <span class="text-white/40">Administrators</span>
                            <span class="font-semibold text-white">{{ $stats['admins'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-white/[0.04] px-4 py-3">
                            <span class="text-white/40">Quizzes</span>
                            <span class="font-semibold text-white">{{ $stats['quizzes'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-white/[0.04] px-4 py-3">
                            <span class="text-white/40">Role</span>
                            <span class="rounded-full bg-[#E50914] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-white shadow-lg shadow-[#E50914]/20">{{ $user->role }}</span>
                        </div>
                    </div>

                    {{-- Date card --}}
                    <div class="mt-8 rounded-3xl border border-[#E50914]/20 bg-[#E50914]/10 p-6">
                        <p class="text-xs uppercase tracking-[0.35em] text-[#E50914]/60">Today</p>
                        <p class="mt-3 text-2xl font-bold text-white">{{ now()->format('F j, Y') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/50">Use the dashboard links to manage the flow for your role.</p>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="lms-card p-6 md:p-8">
                    <h3 class="text-xl font-bold text-white">Quick actions</h3>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('lessons.index') }}" class="lms-button-secondary">All lessons</a>
                        @if ($user->isTutor() || $user->isAdministrator())
                            <a href="{{ route('lessons.create') }}" class="lms-button">New lesson</a>
                        @endif
                    </div>
                </div>
            </aside>
        </div>

        {{-- ── Recent Quiz Attempts ── --}}
        @if ($recentAttempts->count())
            <section class="lms-card overflow-hidden p-6 md:p-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-white">Recent quiz attempts</h3>
                        <p class="mt-1 text-sm text-white/35">Latest submissions and scores.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                    <table class="lms-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Quiz</th>
                                <th>Score</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentAttempts as $attempt)
                                <tr>
                                    <td class="font-medium text-white">{{ $attempt->student->name ?? 'Unknown' }}</td>
                                    <td class="text-white/50">{{ $attempt->quiz->title ?? 'Quiz' }}</td>
                                    <td class="font-semibold text-white">{{ $attempt->score }}/{{ $attempt->total_questions }}</td>
                                    <td class="text-white/30">{{ optional($attempt->completed_at)->format('M j, Y g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
