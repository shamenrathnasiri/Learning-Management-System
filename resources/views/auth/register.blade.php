<x-guest-layout>
    <div class="min-h-screen px-4 py-10">
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl items-center">
            <div class="grid w-full gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                {{-- Left panel --}}
                <section class="hidden rounded-[2rem] border border-white/5 bg-[#111] p-8 text-white shadow-2xl shadow-black/30 lg:block glow-red">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Learning Management System</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight">Join as a student.</h1>
                    <p class="mt-5 max-w-md text-lg leading-8 text-white/50">Create your account to browse lessons, join quizzes, and track your learning progress in one place.</p>

                    <div class="gradient-line mt-8"></div>

                    <div class="mt-8 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition hover:border-white/20">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/30">Access</p>
                            <p class="mt-2 text-lg font-semibold">Students can register directly.</p>
                        </div>
                        <div class="rounded-3xl border border-[#E50914]/30 bg-[#E50914]/10 p-5 transition hover:bg-[#E50914]/15">
                            <p class="text-xs uppercase tracking-[0.3em] text-[#E50914]/60">Security</p>
                            <p class="mt-2 text-lg font-semibold text-white">Tutors and admins are created separately.</p>
                        </div>
                    </div>
                </section>

                {{-- Register form --}}
                <section class="lms-card mx-auto w-full max-w-md p-8 md:p-10">
                    <div class="mb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Create account</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-white">Start learning today</h2>
                        <p class="mt-3 text-sm leading-6 text-white/40">Fill in your details to create a student account.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" class="mt-2 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="mt-2 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-sm text-white/40">
                            Only students can register. Tutors and administrators are added by the system administrator.
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition">Already have an account?</a>
                            <x-primary-button>
                                {{ __('Create account') }}
                            </x-primary-button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
