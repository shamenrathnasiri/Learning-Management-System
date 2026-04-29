<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="lms-card w-full max-w-md p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E50914]">Verification</p>
            <h2 class="mt-2 text-2xl font-black text-white">Verify your email</h2>
            <p class="mt-3 text-sm text-white/40">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-400">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button>
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-white/40 hover:text-[#E50914] transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
