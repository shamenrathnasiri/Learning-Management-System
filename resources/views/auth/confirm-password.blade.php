<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="lms-card w-full max-w-md p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Security</p>
            <h2 class="mt-2 text-2xl font-black text-white">Confirm password</h2>
            <p class="mt-3 text-sm text-white/40">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('Confirm') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
