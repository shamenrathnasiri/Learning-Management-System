<x-guest-layout>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,rgba(229,9,20,0.08),transparent_30%),linear-gradient(180deg,#ffffff_0%,#f5f5f5_100%)] px-4 py-10">
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-6xl items-center">
            <div class="grid w-full gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                <section class="hidden rounded-[2rem] bg-black p-8 text-white shadow-2xl shadow-black/10 lg:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Learning Management System</p>
                    <h1 class="mt-6 text-5xl font-black tracking-tight">Join as a student.</h1>
                    <p class="mt-5 max-w-md text-lg leading-8 text-white/70">Create your account to browse lessons, join quizzes, and track your learning progress in one place.</p>

                    <div class="mt-10 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/40">Access</p>
                            <p class="mt-2 text-lg font-semibold">Students can register directly.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-[#E50914] p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/75">Security</p>
                            <p class="mt-2 text-lg font-semibold text-white">Tutors and admins are created separately.</p>
                        </div>
                    </div>
                </section>

                <section class="lms-card mx-auto w-full max-w-md p-8 md:p-10">
                    <div class="mb-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Create account</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-black">Start learning today</h2>
                        <p class="mt-3 text-sm leading-6 text-black/60">Fill in your details to create a student account.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="text-black" />
                            <x-text-input id="name" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-black" />
                            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-black" />
                            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black" />
                            <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="rounded-2xl border border-black/10 bg-black/5 p-4 text-sm text-black/60">
                            Only students can register. Tutors and administrators are added by the system administrator.
                        </div>

                        <div class="flex items-center justify-between gap-3 pt-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-[#E50914] hover:text-black">Already have an account?</a>
                            <x-primary-button class="lms-button justify-center rounded-full px-6 py-3">
                                {{ __('Create account') }}
                            </x-primary-button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
