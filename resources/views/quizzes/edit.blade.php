<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quizzes</p>
            <h2 class="text-2xl font-bold text-black">Edit quiz</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('quizzes.update', $quiz) }}" class="lms-card space-y-6 p-6">
            @csrf
            @method('PUT')
            @include('quizzes._form', ['quiz' => $quiz])
            <div class="flex justify-end gap-3">
                <a href="{{ route('quizzes.show', $quiz) }}" class="lms-button-secondary">Cancel</a>
                <button class="lms-button" type="submit">Save quiz</button>
            </div>
        </form>
    </div>
</x-app-layout>
