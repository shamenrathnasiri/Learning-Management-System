<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lessons</p>
            <h2 class="text-2xl font-bold text-black">Edit lesson</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('lessons.update', $lesson) }}" enctype="multipart/form-data" class="lms-card space-y-6 p-6">
            @csrf
            @method('PUT')
            @include('lessons._form', ['lesson' => $lesson])
            <div class="flex justify-end gap-3">
                <a href="{{ route('lessons.show', $lesson) }}" class="lms-button-secondary">Cancel</a>
                <button class="lms-button" type="submit">Save changes</button>
            </div>
        </form>
    </div>
</x-app-layout>
