<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quizzes</p>
                <h2 class="text-2xl font-bold text-white">Edit quiz</h2>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-3">
                <a href="{{ route('grading.index-attempts', $quiz) }}" class="lms-button-secondary" title="View and grade student responses">
                    <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Grade responses
                </a>
                <a href="{{ route('quizzes.show', $quiz) }}" class="lms-button-secondary">
                    <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Preview
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('quizzes.update', $quiz) }}" enctype="multipart/form-data" class="lms-card space-y-6 p-6">
            @csrf
            @method('PUT')
            @include('quizzes._form', ['quiz' => $quiz])
            <div class="gradient-line"></div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('quizzes.show', $quiz) }}" class="lms-button-secondary">Cancel</a>
                <button class="lms-button" type="submit">Save quiz</button>
            </div>
        </form>
    </div>
</x-app-layout>
