<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-black">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-2 w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" required>
        @error('name')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-2 w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" required>
        @error('email')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black">Role</label>
        <select name="role" class="mt-2 w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" required>
            @foreach (['student' => 'Student', 'tutor' => 'Tutor', 'admin' => 'Administrator'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? 'student') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black">Password {{ isset($user) ? '(leave empty to keep current)' : '' }}</label>
        <input type="password" name="password" class="mt-2 w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" {{ isset($user) ? '' : 'required' }}>
        @error('password')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black">Confirm password</label>
        <input type="password" name="password_confirmation" class="mt-2 w-full rounded-2xl border-black/10 focus:border-[#E50914] focus:ring-[#E50914]" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>
