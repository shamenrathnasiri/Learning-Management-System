<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Admin</p>
                <h2 class="text-2xl font-bold text-white">Manage users</h2>
            </div>
            <a href="{{ route('admin.users.create') }}" class="lms-button">Create user</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="lms-card overflow-hidden">
            <table class="lms-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="font-medium text-white">{{ $user->name }}</td>
                            <td class="text-white/40 text-sm">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="lms-badge lms-badge-red">{{ $user->role }}</span>
                                @elseif($user->role === 'tutor')
                                    <span class="lms-badge bg-white/10 text-white/70">{{ $user->role }}</span>
                                @else
                                    <span class="lms-badge bg-white/5 text-white/40">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg font-semibold text-sm text-[#E50914] hover:bg-[#E50914]/10 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg font-semibold text-sm text-white/30 hover:text-[#E50914] hover:bg-[#E50914]/5 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">{{ $users->links() }}</div>
    </div>
</x-app-layout>
