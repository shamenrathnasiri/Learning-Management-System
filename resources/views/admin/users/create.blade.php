<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Admin</p>
            <h2 class="text-2xl font-bold text-black">Create user</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="lms-card space-y-6 p-6">
            @csrf
            @include('admin.users._form')
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="lms-button-secondary">Cancel</a>
                <button type="submit" class="lms-button">Create user</button>
            </div>
        </form>
    </div>
</x-app-layout>
