<nav x-data="{ open: false }" class="relative z-50 border-b border-white/5 bg-[#0a0a0a]/80 backdrop-blur-xl">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E50914] text-sm font-bold text-white shadow-lg shadow-[#E50914]/20 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">L</div>
                        <div>
                            <div class="text-[10px] font-semibold uppercase tracking-[0.25em] text-white/30">Learning</div>
                            <div class="text-sm font-bold text-white">Management System</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('lessons.index')" :active="request()->routeIs('lessons.*')">
                        {{ __('Lessons') }}
                    </x-nav-link>
                    @auth
                        @if(auth()->user()->isAdministrator())
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Users') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        @auth
                        <button class="group inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white transition-all duration-300 hover:border-[#E50914]/40 hover:bg-white/10 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <span class="rounded-full bg-[#E50914]/15 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.2em] text-[#E50914]">
                                {{ Auth::user()->role }}</span>
                            <svg class="fill-current h-4 w-4 text-white/40 transition-transform group-hover:text-[#E50914]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        @endauth
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 mt-1 pt-1">
                            @csrf

                            <button type="submit" @click.stop class="block w-full px-4 py-3 text-start text-sm font-semibold leading-5 text-[#ff4d55] hover:bg-[#E50914]/10 hover:text-white focus:outline-none focus:bg-[#E50914]/10 transition duration-200 cursor-pointer">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/50 hover:text-[#E50914] hover:bg-white/5 focus:outline-none transition duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lessons.index')" :active="request()->routeIs('lessons.*')">
                {{ __('Lessons') }}
            </x-responsive-nav-link>
            @auth
                @if(auth()->user()->isAdministrator())
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/40">{{ Auth::user()->email }}</div>
            </div>
            @endauth

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-white/10 pt-1">
                    @csrf

                    <button type="submit" @click.stop class="block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-semibold text-[#ff4d55] hover:text-white hover:bg-[#E50914]/10 hover:border-[#E50914] focus:outline-none transition duration-200 cursor-pointer">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
