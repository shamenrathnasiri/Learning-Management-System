<x-guest-layout>
    <div class="min-h-screen px-4 py-10">
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl items-center">
            <div class="grid w-full gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                {{-- Left panel --}}
                <section class="hidden rounded-[2rem] border border-white/5 bg-[#111] p-8 text-white shadow-2xl shadow-black/30 lg:block glow-red">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Learning Management System</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight">Welcome back.</h1>
                    <p class="mt-5 max-w-md text-lg leading-8 text-white/50">Sign in to manage lessons, run quizzes, and track student progress from a clean role-aware dashboard.</p>

                    <div class="gradient-line mt-8"></div>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition hover:border-white/20">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/30">Focus</p>
                            <p class="mt-2 text-lg font-semibold">Simple, fast, and clear access.</p>
                        </div>
                        <div class="rounded-3xl border border-[#E50914]/30 bg-[#E50914]/10 p-5 transition hover:bg-[#E50914]/15">
                            <p class="text-xs uppercase tracking-[0.3em] text-[#E50914]/60">Roles</p>
                            <p class="mt-2 text-lg font-semibold text-white">Administrator, Tutor, Student</p>
                        </div>
                    </div>
                </section>

                {{-- Login form --}}
                <section class="lms-card mx-auto w-full max-w-md p-8 md:p-10">
                    <div class="mb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Sign in</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-white">Access your account</h2>
                        <p class="mt-3 text-sm leading-6 text-white/40">Use your LMS credentials to continue.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <label for="remember_me" class="flex items-center gap-2 text-sm text-white/50">
                            <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-[#E50914] focus:ring-[#E50914]/30" name="remember">
                            <span>{{ __('Remember me') }}</span>
                        </label>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            @if (Route::has('password.request'))
                                <a class="text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif

                            <x-primary-button>
                                {{ __('Sign in') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if (Route::has('register'))
                        <div class="gradient-line mt-8"></div>
                        <p class="mt-6 text-sm text-white/40">
                            New student? <a href="{{ route('register') }}" class="font-semibold text-[#E50914] hover:text-[#ff4d55] transition">Create an account</a>
                        </p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
