<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Kill the guest layout white wrapper entirely */
        body,
        body > div,
        .min-h-screen,
        .bg-gray-100,
        .bg-white,
        [class*="bg-gray"],
        [class*="bg-white"] {
            background: #001828 !important;
        }

        /* Hide the Laravel logo SVG that guest layout renders above the slot */
        body > div > div:first-child svg,
        .min-h-screen > div:first-child,
        .min-h-screen > div > a:first-child,
        nav ~ div > a svg {
            display: none !important;
        }

        body {
            background: #001828 !important;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ── Animated dot-grid background ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #001828;
        }

        /* Dot grid canvas */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(247,127,0,0.25) 1px, transparent 1px);
            background-size: 36px 36px;
            animation: gridDrift 30s linear infinite;
        }

        @keyframes gridDrift {
            0%   { background-position: 0 0; }
            100% { background-position: 36px 36px; }
        }

        /* Radial vignette so dots fade at edges */
        .bg-vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 80% at 50% 50%,
                transparent 40%,
                #001828 100%
            );
        }

        /* Colour wash blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            animation: drift 14s ease-in-out infinite alternate;
        }

        .blob-1 {
            width: 560px; height: 560px;
            background: #D62828;
            opacity: 0.12;
            top: -160px; left: -120px;
        }

        .blob-2 {
            width: 440px; height: 440px;
            background: #F77F00;
            opacity: 0.10;
            bottom: -100px; right: -100px;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 320px; height: 320px;
            background: #003049;
            opacity: 0.60;
            top: 30%; right: 10%;
            animation-delay: -9s;
        }

        @keyframes drift {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 35px) scale(1.10); }
        }

        /* Scanning horizontal line */
        .scan-line {
            position: absolute;
            left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(247,127,0,0.4) 30%,
                rgba(214,40,40,0.5) 50%,
                rgba(247,127,0,0.4) 70%,
                transparent 100%
            );
            animation: scan 6s ease-in-out infinite;
            opacity: 0.6;
        }

        @keyframes scan {
            0%   { top: -2px; opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.6; }
            100% { top: 100%; opacity: 0; }
        }

        /* ── Kill Breeze guest-layout white wrapper & logo ── */
        .min-h-screen {
            background: transparent !important;
        }
        .min-h-screen > div:first-child {
            display: none !important;
        }
        .min-h-screen > div:last-child,
        .min-h-screen > div > div {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* Card */
        .login-card {
            position: relative;
            z-index: 10;
            width: 420px;
            padding: 52px 44px 44px;
            background: rgba(0, 22, 38, 0.78);
            border: 1px solid rgba(247, 127, 0, 0.18);
            border-radius: 4px;
            backdrop-filter: blur(24px);
            box-shadow:
                0 0 0 1px rgba(214, 40, 40, 0.08),
                0 32px 80px rgba(0,0,0,0.55),
                inset 0 1px 0 rgba(255,255,255,0.05);

            /* Card entrance animation */
            opacity: 0;
            transform: translateY(32px);
            animation: cardReveal 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
        }

        @keyframes cardReveal {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Accent bar at top of card */
        .card-accent {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #D62828 0%, #F77F00 60%, transparent 100%);
            border-radius: 4px 4px 0 0;
        }

        /* Logo / Brand mark */
        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.35s forwards;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #D62828, #F77F00);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 20px; height: 20px;
            fill: #fff;
        }

        .brand-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px;
            letter-spacing: 0.12em;
            color: #fff;
            line-height: 1;
        }

        .brand-sub {
            font-size: 11px;
            font-weight: 300;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-top: 3px;
        }

        h1.login-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 38px;
            letter-spacing: 0.06em;
            color: #fff;
            line-height: 1;
            margin-bottom: 6px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.45s forwards;
        }

        .login-subtitle {
            font-size: 13px;
            font-weight: 300;
            color: rgba(255,255,255,0.38);
            letter-spacing: 0.04em;
            margin-bottom: 32px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.52s forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Form field group */
        .field-group {
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeUp 0.5s ease var(--delay, 0.6s) forwards;
        }

        .field-group:nth-child(1) { --delay: 0.58s; }
        .field-group:nth-child(2) { --delay: 0.66s; }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(247, 127, 0, 0.85);
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            opacity: 0.4;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .field-wrap:focus-within .field-icon {
            opacity: 1;
        }

        /* Override Breeze/Laravel text input */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid rgba(255,255,255,0.10) !important;
            border-radius: 3px !important;
            color: #fff !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 14px !important;
            font-weight: 300 !important;
            padding: 12px 14px 12px 42px !important;
            outline: none !important;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s !important;
            box-shadow: none !important;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            background: rgba(247, 127, 0, 0.05) !important;
            border-color: #F77F00 !important;
            box-shadow: 0 0 0 3px rgba(247, 127, 0, 0.12) !important;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255,255,255,0.18) !important;
        }

        /* Error text */
        .text-sm.text-red-600,
        [class*="text-red"] {
            color: #F77F00 !important;
            font-size: 11px;
            letter-spacing: 0.03em;
            margin-top: 6px;
        }

        /* Remember row */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.74s forwards;
        }

        input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #F77F00;
            cursor: pointer;
            border-radius: 3px;
        }

        .remember-label {
            font-size: 13px;
            font-weight: 300;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.02em;
            cursor: pointer;
        }

        /* Divider */
        .form-divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 24px 0;
            opacity: 0;
            animation: fadeUp 0.4s ease 0.80s forwards;
        }

        /* Action row */
        .action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.86s forwards;
        }

        .forgot-link {
            font-size: 12px;
            font-weight: 400;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            letter-spacing: 0.04em;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .forgot-link:hover {
            color: #F77F00;
        }

        /* Login button */
        .btn-login {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #D62828 0%, #F77F00 100%) !important;
            border: none !important;
            border-radius: 3px !important;
            color: #fff !important;
            font-family: 'Bebas Neue', sans-serif !important;
            font-size: 17px !important;
            letter-spacing: 0.12em !important;
            padding: 13px 30px !important;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s !important;
            white-space: nowrap;
            box-shadow: 0 4px 24px rgba(214, 40, 40, 0.35) !important;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 32px rgba(214, 40, 40, 0.5) !important;
        }

        .btn-login:hover::after {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0) scale(0.98) !important;
        }

        /* Arrow icon on button */
        .btn-arrow {
            width: 16px; height: 16px;
            fill: #fff;
            transition: transform 0.2s;
        }
        .btn-login:hover .btn-arrow {
            transform: translateX(4px);
        }

        /* Auth session status */
        .session-status {
            font-size: 12px;
            color: #F77F00;
            letter-spacing: 0.04em;
            margin-bottom: 20px;
        }

        /* Shimmer on card load */
        .card-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, transparent 40%, rgba(247,127,0,0.06) 50%, transparent 60%);
            transform: translateX(-100%);
            animation: shimmer 1.4s ease 0.3s forwards;
            pointer-events: none;
            border-radius: 4px;
        }

        @keyframes shimmer {
            to { transform: translateX(200%); }
        }
    </style>

    <div class="bg-scene">
        <div class="bg-grid"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="bg-vignette"></div>
        <div class="scan-line"></div>
    </div>

    <div class="login-card">
        <div class="card-accent"></div>
        <div class="card-shimmer"></div>

        <!-- Brand -->
        <div class="brand-mark">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18L20 8.5v7L12 19.82 4 15.5v-7L12 4.18z"/>
                </svg>
            </div>
            <div>
                <div class="brand-name">Fortress</div>
                <div class="brand-sub">Secure Access Portal</div>
            </div>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="session-status">{{ session('status') }}</div>
        @endif

        <h1 class="login-title">Sign In</h1>
        <p class="login-subtitle">Enter your credentials to continue</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="field-group">
                <label class="field-label" for="email">Email Address</label>
                <div class="field-wrap">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#F77F00;">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <x-text-input id="email" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" placeholder="you@example.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="field-group">
                <label class="field-label" for="password">Password</label>
                <div class="field-wrap">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#F77F00;">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <x-text-input id="password" type="password" name="password"
                        required autocomplete="current-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="remember-row">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me" class="remember-label">Keep me signed in</label>
            </div>

            <div class="form-divider"></div>

            <!-- Actions -->
            <div class="action-row">
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif

                <button type="submit" class="btn-login">
                    Log In
                    <svg class="btn-arrow" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                        <path d="M5 12h14M12 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
