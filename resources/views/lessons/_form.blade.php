<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-white/80 mb-2">Lesson Title *</label>
        <input type="text" name="title" value="{{ old('title', $lesson->title ?? '') }}" class="lms-input" placeholder="Enter lesson title" required>
        @error('title')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80 mb-2">Thumbnail Image *</label>
        <div class="relative">
            <input type="file" name="thumbnail" accept="image/*" class="w-full px-4 py-3 rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] text-sm text-white/50 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#E50914] file:text-white hover:file:bg-[#ff1a25] cursor-pointer transition">
            <p class="text-xs text-white/25 mt-2">PNG, JPG up to 2MB</p>
        </div>
        @error('thumbnail')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-white/80 mb-2">Description *</label>
        <div class="mt-2" data-rich-text-editor>
            <div class="lms-rich-editor-toolbar" data-editor-toolbar>
                <button type="button" class="lms-rich-editor-button" data-editor-action="bold" aria-label="Bold">B</button>
                <button type="button" class="lms-rich-editor-button italic" data-editor-action="italic" aria-label="Italic">I</button>
                <button type="button" class="lms-rich-editor-button underline" data-editor-action="underline" aria-label="Underline">U</button>
                <button type="button" class="lms-rich-editor-button" data-editor-action="insertUnorderedList" aria-label="Bullet list">•</button>
                <button type="button" class="lms-rich-editor-button" data-editor-action="insertOrderedList" aria-label="Numbered list">1.</button>
                <button type="button" class="lms-rich-editor-button" data-editor-action="link" aria-label="Insert link">↗</button>
            </div>
            <div
                class="lms-input lms-rich-editor-surface"
                data-editor-surface
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                data-placeholder="Write an engaging lesson description for students"
            >{!! old('description', $lesson->description ?? '') !!}</div>
            <textarea name="description" class="hidden" data-editor-input required>{{ old('description', $lesson->description ?? '') }}</textarea>
        </div>
        @error('description')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80 mb-2">Content Type *</label>
        <select name="content_type" class="lms-input cursor-pointer" required>
            <option value="" disabled @selected(old('content_type', $lesson->content_type ?? '') === '')>Select content type...</option>
            @foreach (['text' => 'Text', 'video' => 'Video link', 'file' => 'File attachment'] as $value => $label)
                <option value="{{ $value }}" @selected(old('content_type', $lesson->content_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('content_type')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-white/80 mb-2">Video URL</label>
        <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url ?? '') }}" class="lms-input" placeholder="https://example.com/video">
        @error('video_url')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-white/80 mb-2">Lesson Content *</label>
        <textarea name="content" rows="7" class="lms-input" placeholder="Write the lesson text or provide supporting instructions for the selected content type." required>{{ old('content', $lesson->content ?? '') }}</textarea>
        @error('content')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="block text-sm font-semibold text-white/80 mb-2">Attachment</label>
        <input type="file" name="attachment" class="w-full px-4 py-3 rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] text-sm text-white/50 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-white/10 file:text-white hover:file:bg-white/20 cursor-pointer transition">
        <p class="text-xs text-white/25 mt-2">PDF, DOC up to 10MB</p>
        @error('attachment')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
    </div>
</div>
