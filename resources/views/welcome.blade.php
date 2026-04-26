<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LMS — Learn. Teach. Track.</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-black antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(229,9,20,0.08),transparent_28%),linear-gradient(180deg,#ffffff_0%,#f5f5f5_100%)]"></div>
            <div class="absolute -left-20 top-10 h-72 w-72 rounded-full bg-[#E50914]/10 blur-3xl"></div>
            <div class="absolute right-0 top-40 h-80 w-80 rounded-full bg-black/5 blur-3xl"></div>

            <header class="relative mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#E50914] text-sm font-black text-white shadow-lg shadow-[#E50914]/20">L</span>
                    <span class="text-lg font-bold tracking-tight text-black">Learning Management System</span>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="lms-button">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="lms-button-secondary">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="lms-button">Create account</a>
                        @endif
                    @endauth
                </div>
            </header>

            <main class="relative mx-auto grid min-h-[calc(100vh-88px)] max-w-7xl items-center gap-14 px-6 py-10 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
                <section class="max-w-2xl">
                    <p class="inline-flex items-center rounded-full border border-black/10 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914] shadow-sm">Modern LMS</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight text-black sm:text-6xl xl:text-7xl">Learn, teach, and test in one focused platform.</h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-black/60">A clean LMS for tutors, students, and administrators with role-based dashboards, lesson cards, quizzes, and fast navigation.</p>

                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                        @guest
                            <a href="{{ route('register') }}" class="lms-button px-8 py-4 text-base">Get started</a>
                            <a href="{{ route('login') }}" class="lms-button-secondary px-8 py-4 text-base">Sign in</a>
                        @endguest
                        @auth
                            <a href="{{ url('/dashboard') }}" class="lms-button px-8 py-4 text-base">Go to dashboard</a>
                        @endauth
                    </div>

                    <div class="mt-12 grid gap-4 sm:grid-cols-3">
                        <div class="lms-card p-5">
                            <p class="text-sm text-black/50">Roles</p>
                            <p class="mt-2 text-2xl font-black text-black">3</p>
                            <p class="mt-1 text-sm text-black/60">Admin, Tutor, Student</p>
                        </div>
                        <div class="lms-card p-5">
                            <p class="text-sm text-black/50">Lessons</p>
                            <p class="mt-2 text-2xl font-black text-black">Cards</p>
                            <p class="mt-1 text-sm text-black/60">Thumbnail-driven layout</p>
                        </div>
                        <div class="lms-card p-5">
                            <p class="text-sm text-black/50">Quizzes</p>
                            <p class="mt-2 text-2xl font-black text-black">Auto</p>
                            <p class="mt-1 text-sm text-black/60">Multiple-choice grading</p>
                        </div>
                    </div>
                </section>

                <section class="relative">
                    <div class="lms-card relative overflow-hidden p-6 sm:p-8">
                        <div class="absolute right-0 top-0 h-32 w-32 rounded-full bg-[#E50914]/10 blur-3xl"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Featured</p>
                                    <h2 class="mt-2 text-2xl font-black text-black">A sharper learning experience</h2>
                                </div>
                                <span class="rounded-full bg-black px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-white">LMS</span>
                            </div>

                            <div class="mt-8 space-y-4">
                                <div class="rounded-3xl border border-black/10 bg-white p-5 shadow-sm">
                                    <p class="text-xs uppercase tracking-[0.3em] text-black/40">Lessons</p>
                                    <h3 class="mt-2 text-lg font-bold text-black">Card-based lesson browsing</h3>
                                    <p class="mt-2 text-sm leading-6 text-black/60">Users browse clean lesson cards with thumbnails, descriptions, and quick access to details.</p>
                                </div>
                                <div class="rounded-3xl border border-black/10 bg-black p-5 text-white shadow-lg shadow-black/10">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/50">Quizzes</p>
                                    <h3 class="mt-2 text-lg font-bold">Built for fast assessment</h3>
                                    <p class="mt-2 text-sm leading-6 text-white/70">Tutors create multiple-choice quizzes and students get immediate automatic grading.</p>
                                </div>
                                <div class="rounded-3xl border border-black/10 bg-[#E50914] p-5 text-white shadow-lg shadow-[#E50914]/20">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/70">Access</p>
                                    <h3 class="mt-2 text-lg font-bold">Role-based dashboards</h3>
                                    <p class="mt-2 text-sm leading-6 text-white/80">Administrator, tutor, and student each see only the tools they need.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
