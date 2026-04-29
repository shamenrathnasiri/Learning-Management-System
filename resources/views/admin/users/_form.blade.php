<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-white/80">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-2 lms-input" required>
        @error('name')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-2 lms-input" required>
        @error('email')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80">Role</label>
        <select name="role" class="mt-2 lms-input cursor-pointer" required>
            @foreach (['student' => 'Student', 'tutor' => 'Tutor', 'admin' => 'Administrator'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? 'student') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80">Password {{ isset($user) ? '(leave empty to keep current)' : '' }}</label>
        <input type="password" name="password" class="mt-2 lms-input" {{ isset($user) ? '' : 'required' }}>
        @error('password')<p class="mt-2 text-sm text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80">Confirm password</label>
        <input type="password" name="password_confirmation" class="mt-2 lms-input" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>
