<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-black mb-2">Lesson Title *</label>
        <input type="text" name="title" value="{{ old('title', $lesson->title ?? '') }}" class="w-full px-4 py-3 rounded-2xl border-2 border-black/10 bg-white text-black placeholder:text-black/40 focus:outline-none focus:border-[#E50914] focus:ring-2 focus:ring-[#E50914]/20 transition" placeholder="Enter lesson title" required>
        @error('title')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black mb-2">Thumbnail Image *</label>
        <div class="relative">
            <input type="file" name="thumbnail" accept="image/*" class="w-full px-4 py-3 rounded-2xl border-2 border-dashed border-black/20 bg-white text-sm text-black/60 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#E50914] file:text-white hover:file:bg-[#E50914]/90 cursor-pointer transition">
            <p class="text-xs text-black/50 mt-2">PNG, JPG up to 2MB</p>
        </div>
        @error('thumbnail')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-black mb-2">Description *</label>
        <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-2xl border-2 border-black/10 bg-white text-black placeholder:text-black/40 focus:outline-none focus:border-[#E50914] focus:ring-2 focus:ring-[#E50914]/20 transition" placeholder="Write a brief description of the lesson" required>{{ old('description', $lesson->description ?? '') }}</textarea>
        @error('description')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black mb-2">Content Type *</label>
        <select name="content_type" class="w-full px-4 py-3 rounded-2xl border-2 border-black/10 bg-white text-black focus:outline-none focus:border-[#E50914] focus:ring-2 focus:ring-[#E50914]/20 transition cursor-pointer" required>
            <option value="" disabled @selected(old('content_type', $lesson->content_type ?? '') === '')>Select content type...</option>
            @foreach (['text' => 'Text', 'video' => 'Video link', 'file' => 'File attachment'] as $value => $label)
                <option value="{{ $value }}" @selected(old('content_type', $lesson->content_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('content_type')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-black mb-2">Video URL</label>
        <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url ?? '') }}" class="w-full px-4 py-3 rounded-2xl border-2 border-black/10 bg-white text-black placeholder:text-black/40 focus:outline-none focus:border-[#E50914] focus:ring-2 focus:ring-[#E50914]/20 transition" placeholder="https://example.com/video">
        @error('video_url')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-black mb-2">Lesson Content *</label>
        <textarea name="content" rows="7" class="w-full px-4 py-3 rounded-2xl border-2 border-black/10 bg-white text-black placeholder:text-black/40 focus:outline-none focus:border-[#E50914] focus:ring-2 focus:ring-[#E50914]/20 transition" placeholder="Write the lesson text or provide supporting instructions for the selected content type." required>{{ old('content', $lesson->content ?? '') }}</textarea>
        @error('content')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-black mb-2">Attachment</label>
        <input type="file" name="attachment" class="w-full px-4 py-3 rounded-2xl border-2 border-dashed border-black/20 bg-white text-sm text-black/60 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-black/80 cursor-pointer transition">
        <p class="text-xs text-black/50 mt-2">PDF, DOC up to 10MB</p>
        @error('attachment')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>
</div>
