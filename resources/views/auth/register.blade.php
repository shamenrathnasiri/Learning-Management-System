<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body,
        body > div,
        .min-h-screen,
        .bg-gray-100,
        .bg-white,
        [class*="bg-gray"],
        [class*="bg-white"] {
            background: #001828 !important;
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

        /* ── Kill Breeze guest-layout white wrapper & logo ── */
        .min-h-screen { background: transparent !important; }
        .min-h-screen > div:first-child { display: none !important; }
        .min-h-screen > div:last-child,
        .min-h-screen > div > div {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* ── Animated dot-grid background ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #001828;
        }

        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(247,127,0,0.25) 1px, transparent 1px);
            background-size: 36px 36px;
            animation: gridDrift 30s linear infinite;
        }

        @keyframes gridDrift {
            0%   { background-position: 0 0; }
            100% { background-position: 36px 36px; }
        }

        .bg-vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 80% at 50% 50%, transparent 40%, #001828 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            animation: drift 14s ease-in-out infinite alternate;
        }

        .blob-1 { width: 560px; height: 560px; background: #D62828; opacity: 0.12; top: -160px; left: -120px; }
        .blob-2 { width: 440px; height: 440px; background: #F77F00; opacity: 0.10; bottom: -100px; right: -100px; animation-delay: -5s; }
        .blob-3 { width: 320px; height: 320px; background: #003049; opacity: 0.60; top: 30%; right: 10%; animation-delay: -9s; }

        @keyframes drift {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 35px) scale(1.10); }
        }

        .scan-line {
            position: absolute;
            left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, rgba(247,127,0,0.4) 30%, rgba(214,40,40,0.5) 50%, rgba(247,127,0,0.4) 70%, transparent 100%);
            animation: scan 6s ease-in-out infinite;
            opacity: 0.6;
        }

        @keyframes scan {
            0%   { top: -2px; opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.6; }
            100% { top: 100%; opacity: 0; }
        }

        /* ── Card ── */
        .register-card {
            position: relative;
            z-index: 10;
            width: 460px;
            padding: 48px 44px 42px;
            background: rgba(0, 22, 38, 0.80);
            border: 1px solid rgba(247, 127, 0, 0.18);
            border-radius: 4px;
            backdrop-filter: blur(24px);
            box-shadow:
                0 0 0 1px rgba(214, 40, 40, 0.08),
                0 32px 80px rgba(0,0,0,0.55),
                inset 0 1px 0 rgba(255,255,255,0.05);
            opacity: 0;
            transform: translateY(32px);
            animation: cardReveal 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
        }

        @keyframes cardReveal {
            to { opacity: 1; transform: translateY(0); }
        }

        .card-accent {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #F77F00 0%, #D62828 60%, transparent 100%);
            border-radius: 4px 4px 0 0;
        }

        .card-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, transparent 40%, rgba(247,127,0,0.05) 50%, transparent 60%);
            transform: translateX(-100%);
            animation: shimmer 1.4s ease 0.3s forwards;
            pointer-events: none;
            border-radius: 4px;
        }

        @keyframes shimmer {
            to { transform: translateX(200%); }
        }

        /* ── Brand ── */
        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.35s forwards;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #F77F00, #D62828);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
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

        h1.reg-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 38px;
            letter-spacing: 0.06em;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.44s forwards;
        }

        .reg-subtitle {
            font-size: 13px;
            font-weight: 300;
            color: rgba(255,255,255,0.36);
            letter-spacing: 0.04em;
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.50s forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Two-column grid for name + email */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field-full { grid-column: 1 / -1; }

        /* ── Field group ── */
        .field-group {
            margin-bottom: 0;
            opacity: 0;
            animation: fadeUp 0.5s ease var(--delay, 0.58s) forwards;
        }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(247, 127, 0, 0.85);
            margin-bottom: 7px;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            opacity: 0.4;
            transition: opacity 0.2s;
            pointer-events: none;
            color: #F77F00;
        }

        .field-wrap:focus-within .field-icon { opacity: 1; }

        /* ── Input overrides ── */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255,255,255,0.04) !important;
            border: 1px solid rgba(255,255,255,0.10) !important;
            border-radius: 3px !important;
            color: #fff !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 14px !important;
            font-weight: 300 !important;
            padding: 11px 12px 11px 38px !important;
            outline: none !important;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s !important;
            box-shadow: none !important;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            background: rgba(247, 127, 0, 0.05) !important;
            border-color: #F77F00 !important;
            box-shadow: 0 0 0 3px rgba(247, 127, 0, 0.12) !important;
        }

        input::placeholder { color: rgba(255,255,255,0.18) !important; }

        /* Strength bar for password */
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 6px;
            height: 3px;
        }

        .strength-bar span {
            flex: 1;
            border-radius: 2px;
            background: rgba(255,255,255,0.08);
            transition: background 0.3s;
        }

        /* Error text */
        [class*="text-red"] {
            color: #F77F00 !important;
            font-size: 11px;
            letter-spacing: 0.03em;
            margin-top: 5px;
        }

        /* Divider */
        .form-divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 24px 0;
            opacity: 0;
            animation: fadeUp 0.4s ease 0.90s forwards;
        }

        /* ── Action row ── */
        .action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.95s forwards;
        }

        .login-link {
            font-size: 12px;
            font-weight: 400;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            letter-spacing: 0.04em;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .login-link span {
            color: rgba(247,127,0,0.7);
            transition: color 0.2s;
        }

        .login-link:hover span { color: #F77F00; }
        .login-link:hover { color: rgba(255,255,255,0.55); }

        /* ── Register button ── */
        .btn-register {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #F77F00 0%, #D62828 100%) !important;
            border: none !important;
            border-radius: 3px !important;
            color: #fff !important;
            font-family: 'Bebas Neue', sans-serif !important;
            font-size: 17px !important;
            letter-spacing: 0.12em !important;
            padding: 13px 28px !important;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s !important;
            white-space: nowrap;
            box-shadow: 0 4px 24px rgba(247, 127, 0, 0.30) !important;
        }

        .btn-register::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.14), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-register:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 32px rgba(247, 127, 0, 0.45) !important;
        }

        .btn-register:hover::after { opacity: 1; }
        .btn-register:active { transform: translateY(0) scale(0.98) !important; }

        .btn-arrow {
            width: 15px; height: 15px;
            transition: transform 0.2s;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .btn-register:hover .btn-arrow { transform: translateX(4px); }

        /* Step counter dots */
        .step-dots {
            display: flex;
            gap: 6px;
            margin-bottom: 24px;
            opacity: 0;
            animation: fadeUp 0.4s ease 0.32s forwards;
        }

        .step-dot {
            width: 24px; height: 3px;
            border-radius: 2px;
            background: rgba(255,255,255,0.12);
        }

        .step-dot.active {
            background: linear-gradient(90deg, #F77F00, #D62828);
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

    <div class="register-card">
        <div class="card-accent"></div>
        <div class="card-shimmer"></div>

        <!-- Brand -->
        <div class="brand-mark">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.18L20 8.5v7L12 19.82 4 15.5v-7L12 4.18z"/>
                </svg>
            </div>
            <div>
                <div class="brand-name">Fortress</div>
                <div class="brand-sub">Create your account</div>
            </div>
        </div>

        <!-- Progress dots -->
        <div class="step-dots">
            <div class="step-dot active"></div>
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
        </div>

        <h1 class="reg-title">Register</h1>
        <p class="reg-subtitle">Fill in the details below to get started</p>

        <div class="mb-4 text-sm text-gray-600">
            Only students can register. Lecturers and admins must use credentials provided by the system administrator.
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="fields-grid" style="gap:16px; margin-bottom:16px;">

                <!-- Name -->
                <div class="field-group" style="--delay:0.58s;">
                    <label class="field-label" for="name">Full Name</label>
                    <div class="field-wrap">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <x-text-input id="name" type="text" name="name" :value="old('name')"
                            required autofocus autocomplete="name" placeholder="John Doe" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="field-group" style="--delay:0.65s;">
                    <label class="field-label" for="email">Email</label>
                    <div class="field-wrap">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <x-text-input id="email" type="email" name="email" :value="old('email')"
                            required autocomplete="username" placeholder="you@example.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="field-group field-full" style="--delay:0.72s;">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <x-text-input id="password" type="password" name="password"
                            required autocomplete="new-password" placeholder="Min. 8 characters"
                            oninput="updateStrength(this.value)" />
                    </div>
                    <!-- Strength indicator -->
                    <div class="strength-bar" id="strengthBar">
                        <span id="s1"></span><span id="s2"></span>
                        <span id="s3"></span><span id="s4"></span>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="field-group field-full" style="--delay:0.79s;">
                    <label class="field-label" for="password_confirmation">Confirm Password</label>
                    <div class="field-wrap">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                            required autocomplete="new-password" placeholder="Repeat password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

            </div>

            <div class="form-divider"></div>

            <div class="action-row">
                <a class="login-link" href="{{ route('login') }}">
                    Already have an account? <span>Sign in →</span>
                </a>

                <button type="submit" class="btn-register">
                    Create Account
                    <svg class="btn-arrow" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateStrength(val) {
            const bars = [
                document.getElementById('s1'),
                document.getElementById('s2'),
                document.getElementById('s3'),
                document.getElementById('s4'),
            ];
            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['#D62828', '#F77F00', '#f0c040', '#4caf7d'];
            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
            });
        }
    </script>
</x-guest-layout>
