<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="lms-card w-full max-w-md p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Reset Password</p>
            <h2 class="mt-2 text-2xl font-black text-white">Forgot your password?</h2>
            <p class="mt-3 text-sm text-white/40">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
