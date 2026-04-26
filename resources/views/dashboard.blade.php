<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Role dashboard</p>
                <h2 class="text-3xl font-extrabold tracking-tight text-black">{{ ucfirst($user->role) }} overview</h2>
            </div>
            <p class="max-w-2xl text-sm text-black/60">Track lessons, quizzes, and attempts with a clear layout and fast actions tailored to your role.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="lms-card p-6">
                <p class="text-sm font-medium text-black/50">Students</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-black">{{ $stats['students'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Active users</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-sm font-medium text-black/50">Tutors</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-black">{{ $stats['tutors'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Content creators</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-sm font-medium text-black/50">Lessons</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-black">{{ $stats['lessons'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Published modules</p>
            </div>
            <div class="lms-card p-6">
                <p class="text-sm font-medium text-black/50">Quiz attempts</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-black">{{ $stats['attempts'] }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.25em] text-[#E50914]">Tracked results</p>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
            <section class="lms-card p-6 md:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-black">Recent lessons</h3>
                        <p class="mt-1 text-sm text-black/50">Latest content added to the platform.</p>
                    </div>
                    <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Browse lessons</a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @forelse ($recentLessons as $lesson)
                        <a href="{{ route('lessons.show', $lesson) }}" class="group rounded-3xl border border-black/10 bg-white p-5 transition hover:-translate-y-1 hover:border-[#E50914] hover:shadow-lg">
                            <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-black/40">
                                <span>{{ $lesson->content_type }}</span>
                                <span class="text-[#E50914]">{{ $lesson->tutor->name ?? 'Tutor' }}</span>
                            </div>
                            <h4 class="mt-4 text-lg font-bold text-black group-hover:text-[#E50914]">{{ $lesson->title }}</h4>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-black/60">{{ $lesson->description }}</p>
                        </a>
                    @empty
                        <div class="rounded-3xl border border-dashed border-black/10 bg-black/5 p-6 text-sm text-black/50">
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

            <aside class="space-y-6">
                <div class="lms-card p-6 md:p-8">
                    <h3 class="text-xl font-bold text-black">System summary</h3>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between rounded-2xl bg-black/5 px-4 py-3">
                            <span class="text-black/60">Administrators</span>
                            <span class="font-semibold text-black">{{ $stats['admins'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-black/5 px-4 py-3">
                            <span class="text-black/60">Quizzes</span>
                            <span class="font-semibold text-black">{{ $stats['quizzes'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-black/5 px-4 py-3">
                            <span class="text-black/60">Role</span>
                            <span class="rounded-full bg-[#E50914] px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-white">{{ $user->role }}</span>
                        </div>
                    </div>

                    <div class="mt-8 rounded-3xl bg-black p-6 text-white">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Today</p>
                        <p class="mt-3 text-2xl font-bold">{{ now()->format('F j, Y') }}</p>
                        <p class="mt-2 text-sm leading-6 text-white/70">Use the dashboard links to manage the flow for your role.</p>
                    </div>
                </div>

                <div class="lms-card p-6 md:p-8">
                    <h3 class="text-xl font-bold text-black">Quick actions</h3>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('lessons.index') }}" class="lms-button-secondary">All lessons</a>
                        @if ($user->isTutor() || $user->isAdministrator())
                            <a href="{{ route('lessons.create') }}" class="lms-button">New lesson</a>
                        @endif
                    </div>
                </div>
            </aside>
        </div>

        @if ($recentAttempts->count())
            <section class="lms-card overflow-hidden p-6 md:p-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-black">Recent quiz attempts</h3>
                        <p class="mt-1 text-sm text-black/50">Latest submissions and scores.</p>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-black/10 bg-white">
                    <table class="min-w-full divide-y divide-black/10 text-left text-sm">
                        <thead class="bg-black/5 text-black/60">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Student</th>
                                <th class="px-4 py-3 font-semibold">Quiz</th>
                                <th class="px-4 py-3 font-semibold">Score</th>
                                <th class="px-4 py-3 font-semibold">Completed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/10">
                            @foreach ($recentAttempts as $attempt)
                                <tr class="bg-white hover:bg-black/[0.02]">
                                    <td class="px-4 py-3 font-medium text-black">{{ $attempt->student->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-3 text-black/70">{{ $attempt->quiz->title ?? 'Quiz' }}</td>
                                    <td class="px-4 py-3 font-semibold text-black">{{ $attempt->score }}/{{ $attempt->total_questions }}</td>
                                    <td class="px-4 py-3 text-black/50">{{ optional($attempt->completed_at)->format('M j, Y g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
