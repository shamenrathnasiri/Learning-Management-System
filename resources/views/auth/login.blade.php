<x-guest-layout>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,rgba(229,9,20,0.08),transparent_30%),linear-gradient(180deg,#ffffff_0%,#f5f5f5_100%)] px-4 py-10">
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl items-center">
            <div class="grid w-full gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                <section class="hidden rounded-[2rem] bg-black p-8 text-white shadow-2xl shadow-black/10 lg:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Learning Management System</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight">Welcome back.</h1>
                    <p class="mt-5 max-w-md text-lg leading-8 text-white/70">Sign in to manage lessons, run quizzes, and track student progress from a clean role-aware dashboard.</p>

                    <div class="mt-10 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/40">Focus</p>
                            <p class="mt-2 text-lg font-semibold">Simple, fast, and clear access.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-[#E50914] p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/75">Roles</p>
                            <p class="mt-2 text-lg font-semibold text-white">Administrator, Tutor, Student</p>
                        </div>
                    </div>
                </section>

                <section class="lms-card mx-auto w-full max-w-md p-8 md:p-10">
                    <div class="mb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Sign in</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-black">Access your account</h2>
                        <p class="mt-3 text-sm leading-6 text-black/60">Use your LMS credentials to continue.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-black" />
                            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-black" />
                            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <label for="remember_me" class="flex items-center gap-2 text-sm text-black/60">
                            <input id="remember_me" type="checkbox" class="rounded border-black/20 text-[#E50914] focus:ring-[#E50914]" name="remember">
                            <span>{{ __('Remember me') }}</span>
                        </label>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            @if (Route::has('password.request'))
                                <a class="text-sm font-semibold text-[#E50914] hover:text-black" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif

                            <x-primary-button class="lms-button justify-center rounded-full px-6 py-3">
                                {{ __('Sign in') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if (Route::has('register'))
                        <p class="mt-8 text-sm text-black/60">
                            New student? <a href="{{ route('register') }}" class="font-semibold text-[#E50914] hover:text-black">Create an account</a>
                        </p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
