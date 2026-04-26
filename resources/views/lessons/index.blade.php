<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lessons</p>
                <h2 class="text-2xl font-bold text-black">Browse learning content</h2>
            </div>
            @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                <a href="{{ route('lessons.create') }}" class="lms-button">Create lesson</a>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($lessons as $lesson)
                <a href="{{ route('lessons.show', $lesson) }}" class="group lms-card overflow-hidden transition hover:-translate-y-2">
                    <div class="relative h-48 bg-black/5 overflow-hidden">
                        @if ($lesson->thumbnail_path)
                            <img src="{{ asset('storage/'.$lesson->thumbnail_path) }}" alt="{{ $lesson->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-black/80 to-[#E50914] text-4xl font-black text-white">{{ strtoupper(substr($lesson->title, 0, 1)) }}</div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-black shadow-sm">{{ $lesson->content_type }}</div>
                    </div>
                    <div class="p-6">
                        <p class="text-xs font-semibold text-[#E50914] uppercase tracking-[0.2em]">{{ $lesson->tutor->name ?? 'Tutor' }}</p>
                        <h3 class="mt-2 text-lg font-bold text-black group-hover:text-[#E50914] transition">{{ $lesson->title }}</h3>
                        <p class="mt-3 line-clamp-2 text-sm text-black/60">{{ $lesson->description }}</p>
                        <div class="mt-4 flex items-center text-[#E50914] font-semibold text-sm opacity-0 group-hover:opacity-100 transition">
                            View lesson →
                        </div>
                    </div>
                </a>
            @empty
                <div class="lms-card p-8 md:col-span-2 xl:col-span-3 text-center">
                    <svg class="w-12 h-12 mx-auto text-black/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path></svg>
                    <p class="text-black/60 font-medium">No lessons have been published yet</p>
                    <p class="text-sm text-black/40 mt-1">Check back soon for new content</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $lessons->links() }}</div>
    </div>
</x-app-layout>
