<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LMS — Learning Management System</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            navy:   '#003049',
                            crimson:'#D62828',
                            orange: '#F77F00',
                        },
                        fontFamily: {
                            display: ['Syne', 'sans-serif'],
                            body:    ['DM Sans', 'sans-serif'],
                        },
                        animation: {
                            'float':      'float 6s ease-in-out infinite',
                            'float-slow': 'float 9s ease-in-out infinite reverse',
                            'slide-up':   'slideUp .8s cubic-bezier(.16,1,.3,1) both',
                            'fade-in':    'fadeIn .6s ease both',
                            'spin-slow':  'spin 20s linear infinite',
                            'pulse-ring': 'pulseRing 2.5s ease-out infinite',
                        },
                        keyframes: {
                            float:      { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-18px)' } },
                            slideUp:    { from:{ opacity:'0', transform:'translateY(40px)' }, to:{ opacity:'1', transform:'translateY(0)' } },
                            fadeIn:     { from:{ opacity:'0' }, to:{ opacity:'1' } },
                            pulseRing:  { '0%':{ transform:'scale(.9)', opacity:'.8' }, '70%':{ transform:'scale(1.3)', opacity:'0' }, '100%':{ opacity:'0' } },
                        },
                    },
                },
            }
        </script>

        <style>
            body { font-family: 'DM Sans', sans-serif; }
            h1, h2, .font-display { font-family: 'Syne', sans-serif; }

            /* Animated gradient mesh background */
            .mesh-bg {
                background-color: #003049;
                background-image:
                    radial-gradient(ellipse 60% 50% at 20% 30%, rgba(214,40,40,.25) 0%, transparent 60%),
                    radial-gradient(ellipse 50% 60% at 80% 70%, rgba(247,127,0,.20) 0%, transparent 60%),
                    radial-gradient(ellipse 40% 40% at 60% 10%, rgba(247,127,0,.12) 0%, transparent 55%);
            }

            /* Noise grain overlay */
            .grain::before {
                content:'';
                position:fixed; inset:0; z-index:1; pointer-events:none;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
                background-repeat: repeat;
                background-size: 128px;
                opacity:.35;
            }

            .delay-100 { animation-delay:.1s }
            .delay-200 { animation-delay:.2s }
            .delay-300 { animation-delay:.3s }
            .delay-400 { animation-delay:.4s }
            .delay-500 { animation-delay:.5s }
            .delay-600 { animation-delay:.6s }
            .delay-700 { animation-delay:.7s }

            /* Glow button */
            .btn-primary {
                position:relative; overflow:hidden;
                background: linear-gradient(135deg, #D62828, #F77F00);
                transition: transform .2s, box-shadow .2s;
            }
            .btn-primary::after {
                content:''; position:absolute; inset:0;
                background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);
                opacity:0; transition:opacity .2s;
            }
            .btn-primary:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(214,40,40,.45); }
            .btn-primary:hover::after { opacity:1; }

            .btn-outline {
                border:1.5px solid rgba(255,255,255,.25);
                transition: border-color .2s, background .2s, transform .2s;
                backdrop-filter: blur(8px);
            }
            .btn-outline:hover {
                border-color: rgba(247,127,0,.7);
                background: rgba(247,127,0,.1);
                transform: translateY(-2px);
            }

            /* Card hover */
            .stat-card {
                background: rgba(255,255,255,.04);
                border: 1px solid rgba(255,255,255,.08);
                backdrop-filter: blur(12px);
                transition: transform .3s, background .3s, border-color .3s;
            }
            .stat-card:hover {
                transform: translateY(-4px);
                background: rgba(255,255,255,.07);
                border-color: rgba(247,127,0,.3);
            }

            /* Orbit ring */
            .orbit-ring {
                border: 1px solid rgba(247,127,0,.15);
                border-radius: 50%;
                position: absolute;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width:6px }
            ::-webkit-scrollbar-track { background:#002438 }
            ::-webkit-scrollbar-thumb { background:#D62828; border-radius:3px }
        </style>
    </head>

    <body class="mesh-bg grain min-h-screen text-white overflow-x-hidden">

        <!-- Animated background orbs -->
        <div class="fixed inset-0 pointer-events-none z-0" aria-hidden="true">
            <div class="animate-float absolute top-[8%] left-[5%] w-64 h-64 rounded-full opacity-10"
                 style="background:radial-gradient(circle,#D62828,transparent 70%)"></div>
            <div class="animate-float-slow absolute bottom-[15%] right-[8%] w-80 h-80 rounded-full opacity-10"
                 style="background:radial-gradient(circle,#F77F00,transparent 70%)"></div>
            <div class="animate-float absolute top-[45%] right-[20%] w-40 h-40 rounded-full opacity-8"
                 style="background:radial-gradient(circle,#D62828,transparent 70%); animation-delay:3s"></div>

            <!-- Orbit rings -->
            <div class="orbit-ring animate-spin-slow"
                 style="width:480px;height:480px;top:50%;left:50%;transform:translate(-50%,-50%) rotate(0deg)"></div>
            <div class="orbit-ring"
                 style="width:640px;height:640px;top:50%;left:50%;transform:translate(-50%,-50%);border-color:rgba(214,40,40,.08);animation:spin 30s linear infinite reverse"></div>

            <!-- Dot grid -->
            <svg class="absolute inset-0 w-full h-full opacity-[.04]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1.5" cy="1.5" r="1.5" fill="#F77F00"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>

        <!-- ─── NAVBAR ─────────────────────────────────────────────── -->
        <header class="relative z-10 flex items-center justify-between px-6 md:px-12 py-5 animate-fade-in">
            <div class="flex items-center gap-3">
                <!-- Logo mark -->
                <div class="relative w-9 h-9">
                    <div class="animate-pulse-ring absolute inset-0 rounded-lg bg-orange opacity-60"></div>
                    <div class="relative w-9 h-9 rounded-lg flex items-center justify-center font-display font-800 text-white text-sm"
                         style="background:linear-gradient(135deg,#D62828,#F77F00)">LM</div>
                </div>
                <span class="font-display font-bold text-lg tracking-tight text-white/90">
                    Le<span class="text-orange">rn</span>ing MS
                </span>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm text-white/60 font-body">
                <a href="#" class="hover:text-white transition-colors">Courses</a>
                <a href="#" class="hover:text-white transition-colors">Features</a>
                <a href="#" class="hover:text-white transition-colors">Pricing</a>
                <a href="#" class="hover:text-white transition-colors">About</a>
            </nav>

            @if (Route::has('login'))
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="btn-primary px-5 py-2 rounded-full text-sm font-display font-semibold text-white">
                            Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-outline px-5 py-2 rounded-full text-sm font-body text-white/80">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="btn-primary px-5 py-2 rounded-full text-sm font-display font-semibold text-white">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </header>

        <!-- ─── HERO ───────────────────────────────────────────────── -->
        <main class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-90px)] text-center px-6 py-12">

            <!-- Badge -->
            <div class="animate-slide-up delay-100 mb-6 inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-body font-medium text-orange"
                 style="background:rgba(247,127,0,.12);border:1px solid rgba(247,127,0,.25)">
                <span class="w-1.5 h-1.5 rounded-full bg-orange animate-pulse inline-block"></span>
                Now live — 500+ courses available
            </div>

            <!-- Headline -->
            <h1 class="animate-slide-up delay-200 font-display font-extrabold text-5xl md:text-7xl xl:text-8xl leading-[1.04] tracking-tight max-w-4xl mb-6">
                <span class="text-white">Unlock your</span><br>
                <span style="background:linear-gradient(135deg,#D62828 10%,#F77F00 90%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
                    full potential.
                </span>
            </h1>

            <!-- Sub -->
            <p class="animate-slide-up delay-300 font-body font-light text-white/55 text-lg md:text-xl max-w-xl mb-12 leading-relaxed">
                A modern platform to learn, teach, and grow. Everything you need to build knowledge — in one beautifully designed space.
            </p>

            <!-- CTA buttons — centred -->
            @if (Route::has('login'))
                @guest
                    <div class="animate-slide-up delay-400 flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="btn-primary w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-display font-bold text-white tracking-wide shadow-lg">
                                Create Free Account →
                            </a>
                        @endif
                        <a href="{{ route('login') }}"
                           class="btn-outline w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-body text-white/75">
                            Sign in to continue
                        </a>
                    </div>
                @endguest
                @auth
                    <div class="animate-slide-up delay-400 flex items-center justify-center mb-16">
                        <a href="{{ url('/dashboard') }}"
                           class="btn-primary px-10 py-4 rounded-2xl text-base font-display font-bold text-white tracking-wide shadow-lg">
                            Go to Dashboard →
                        </a>
                    </div>
                @endauth
            @endif

            <!-- Stats row -->
            <div class="animate-slide-up delay-500 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl w-full mb-20">
                <div class="stat-card rounded-2xl px-6 py-5 text-left">
                    <p class="font-display font-bold text-3xl text-orange">12k+</p>
                    <p class="font-body text-sm text-white/50 mt-1">Active learners</p>
                </div>
                <div class="stat-card rounded-2xl px-6 py-5 text-left">
                    <p class="font-display font-bold text-3xl text-crimson">500+</p>
                    <p class="font-body text-sm text-white/50 mt-1">Expert courses</p>
                </div>
                <div class="stat-card rounded-2xl px-6 py-5 text-left">
                    <p class="font-display font-bold text-3xl" style="color:#F77F00">98%</p>
                    <p class="font-body text-sm text-white/50 mt-1">Satisfaction rate</p>
                </div>
            </div>

            <!-- Feature pills -->
            <div class="animate-slide-up delay-600 flex flex-wrap items-center justify-center gap-3 text-xs text-white/40 font-body">
                @foreach(['Live Classes','Progress Tracking','Certificates','AI Tutor','Team Plans','Mobile App'] as $feat)
                    <span class="px-3 py-1.5 rounded-full"
                          style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)">
                        {{ $feat }}
                    </span>
                @endforeach
            </div>
        </main>

        <!-- ─── FOOTER ─────────────────────────────────────────────── -->
        <footer class="relative z-10 text-center py-8 text-white/20 text-xs font-body border-t border-white/5">
            © {{ date('Y') }} Learning Management System · Built with ❤️ and Laravel
        </footer>

    </body>
</html>
