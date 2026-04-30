<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lessons</p>
                <h2 class="text-2xl font-bold text-white">Browse learning content</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                    <a href="{{ route('lessons.create') }}" class="lms-button-secondary">Schedule live class</a>
                    <a href="{{ route('lessons.create') }}" class="lms-button">Create lesson</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($lessons as $lesson)
                <a href="{{ route('lessons.show', $lesson) }}" class="group lms-card overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-[#E50914]/10">
                    <div class="relative h-48 bg-white/5 overflow-hidden">
                        @if ($lesson->thumbnail_path)
                            <img src="{{ asset('storage/'.$lesson->thumbnail_path) }}" alt="{{ $lesson->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent opacity-60"></div>
                        @else
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-[#1a1a1a] to-[#E50914]/30 text-4xl font-black text-white">{{ strtoupper(substr($lesson->title, 0, 1)) }}</div>
                        @endif
                        <div class="absolute top-3 right-3 rounded-full border border-white/20 bg-black/60 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm">{{ $lesson->content_type }}</div>
                    </div>
                    <div class="p-6">
                        <p class="text-xs font-semibold text-[#E50914] uppercase tracking-[0.2em]">{{ $lesson->tutor->name ?? 'Tutor' }}</p>
                        <h3 class="mt-2 text-lg font-bold text-white group-hover:text-[#E50914] transition-colors duration-300">{{ $lesson->title }}</h3>
                        <p class="mt-3 line-clamp-2 text-sm text-white/40">{{ \Illuminate\Support\Str::limit(strip_tags($lesson->description), 120) }}</p>
                        @if ($lesson->live_class_provider && $lesson->live_class_start_at)
                            <p class="mt-3 inline-flex rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff8088]">
                                {{ ucfirst(str_replace('_', ' ', $lesson->live_class_provider)) }} · {{ $lesson->live_class_start_at->format('M j, g:i A') }}
                            </p>
                        @endif
                        <div class="mt-4 flex items-center text-[#E50914] font-semibold text-sm opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-0 group-hover:translate-x-1">
                            View lesson →
                        </div>
                    </div>
                </a>
            @empty
                <div class="lms-card p-8 md:col-span-2 xl:col-span-3 text-center">
                    <svg class="w-12 h-12 mx-auto text-white/15 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path></svg>
                    <p class="text-white/50 font-medium">No lessons have been published yet</p>
                    <p class="text-sm text-white/25 mt-1">Check back soon for new content</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $lessons->links() }}</div>
    </div>
</x-app-layout>
