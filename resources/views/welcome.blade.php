<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LMS — Learn. Teach. Track.</title>
        <meta name="description" content="A modern Learning Management System for tutors, students, and administrators with role-based dashboards, lesson cards, quizzes, and fast navigation.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#0a0a0a] text-white antialiased overflow-x-hidden">
        <div class="relative">
            {{-- ── Ambient Background Glows ── --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-40 -top-40 h-[500px] w-[500px] rounded-full bg-[#E50914]/10 blur-[120px]"></div>
                <div class="absolute right-0 top-1/3 h-[400px] w-[400px] rounded-full bg-[#E50914]/5 blur-[100px]"></div>
                <div class="absolute bottom-0 left-1/3 h-[300px] w-[300px] rounded-full bg-white/[0.02] blur-[80px]"></div>
            </div>

            {{-- ── Navigation ── --}}
            <header class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                <a href="{{ url('/') }}" class="group flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E50914] text-base font-black text-white shadow-lg shadow-[#E50914]/30 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">L</span>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-[0.3em] text-white/40">Learning</span>
                        <span class="block text-lg font-bold tracking-tight text-white">Management System</span>
                    </div>
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

            {{-- ── Hero Section ── --}}
            <main class="relative z-10 mx-auto grid min-h-[calc(100vh-88px)] max-w-7xl items-center gap-14 px-6 py-10 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
                <section class="max-w-2xl fade-up">
                    <p class="inline-flex items-center gap-2 rounded-full border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914] pulse-glow">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#E50914] animate-pulse"></span>
                        Modern LMS
                    </p>
                    <h1 class="mt-8 text-5xl font-black tracking-tight text-white sm:text-6xl xl:text-7xl">
                        Learn, teach, and test in <span class="text-[#E50914]">one</span> focused platform.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-white/50">A clean LMS for tutors, students, and administrators with role-based dashboards, lesson cards, quizzes, and fast navigation.</p>

                    <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                        @guest
                            <a href="{{ route('register') }}" class="lms-button px-8 py-4 text-base">Get started</a>
                            <a href="{{ route('login') }}" class="lms-button-secondary px-8 py-4 text-base">Sign in</a>
                        @endguest
                        @auth
                            <a href="{{ url('/dashboard') }}" class="lms-button px-8 py-4 text-base">Go to dashboard</a>
                        @endauth
                    </div>

                    {{-- ── Stat Cards ── --}}
                    <div class="mt-14 grid gap-4 sm:grid-cols-3">
                        <div class="lms-card group p-5" style="animation-delay: 0.1s">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/30">Roles</p>
                            <p class="mt-3 text-3xl font-black text-white">3</p>
                            <p class="mt-1 text-sm text-white/40">Admin, Tutor, Student</p>
                            <div class="mt-3 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
                        </div>
                        <div class="lms-card group p-5" style="animation-delay: 0.2s">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/30">Lessons</p>
                            <p class="mt-3 text-3xl font-black text-white">Cards</p>
                            <p class="mt-1 text-sm text-white/40">Thumbnail-driven layout</p>
                            <div class="mt-3 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
                        </div>
                        <div class="lms-card group p-5" style="animation-delay: 0.3s">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/30">Quizzes</p>
                            <p class="mt-3 text-3xl font-black text-white">Auto</p>
                            <p class="mt-1 text-sm text-white/40">Multiple-choice grading</p>
                            <div class="mt-3 h-0.5 w-8 rounded-full bg-[#E50914] transition-all duration-500 group-hover:w-full"></div>
                        </div>
                    </div>
                </section>

                {{-- ── Feature Showcase ── --}}
                <section class="relative fade-up" style="animation-delay: 0.3s">
                    <div class="lms-card relative overflow-hidden p-6 sm:p-8 glow-red">
                        {{-- Decorative orb --}}
                        <div class="absolute -right-20 -top-20 h-60 w-60 rounded-full bg-[#E50914]/10 blur-[60px]"></div>
                        <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-white/[0.03] blur-[40px]"></div>

                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Featured</p>
                                    <h2 class="mt-2 text-2xl font-black text-white">A sharper learning experience</h2>
                                </div>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.25em] text-black">LMS</span>
                            </div>

                            <div class="gradient-line mt-6"></div>

                            <div class="mt-6 space-y-4">
                                <div class="group rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:border-white/20 hover:bg-white/[0.06]">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/30">Lessons</p>
                                    <h3 class="mt-2 text-lg font-bold text-white">Card-based lesson browsing</h3>
                                    <p class="mt-2 text-sm leading-6 text-white/50">Users browse clean lesson cards with thumbnails, descriptions, and quick access to details.</p>
                                </div>
                                <div class="group rounded-3xl border border-white/10 bg-white/[0.05] p-5 transition-all duration-300 hover:border-[#E50914]/30 hover:bg-[#E50914]/5">
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/30">Quizzes</p>
                                    <h3 class="mt-2 text-lg font-bold text-white">Built for fast assessment</h3>
                                    <p class="mt-2 text-sm leading-6 text-white/50">Tutors create multiple-choice quizzes and students get immediate automatic grading.</p>
                                </div>
                                <div class="group rounded-3xl border border-[#E50914]/30 bg-[#E50914]/10 p-5 transition-all duration-300 hover:bg-[#E50914]/15">
                                    <p class="text-xs uppercase tracking-[0.3em] text-[#E50914]/70">Access</p>
                                    <h3 class="mt-2 text-lg font-bold text-white">Role-based dashboards</h3>
                                    <p class="mt-2 text-sm leading-6 text-white/60">Administrator, tutor, and student each see only the tools they need.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            {{-- ── Footer gradient line ── --}}
            <div class="gradient-line mx-auto max-w-7xl"></div>
        </div>
    </body>
</html>
