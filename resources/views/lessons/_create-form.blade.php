<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Lesson title *</label>
        <input type="text" name="title" value="{{ old('title') }}" class="lms-input" placeholder="Enter lesson title" required>
        @error('title')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Course *</label>
        <select name="course_id" class="lms-input cursor-pointer" required>
            <option value="" disabled @selected(old('course_id') === null || old('course_id') === '')>Select course...</option>
            @foreach ($courses as $id => $label)
                <option value="{{ $id }}" @selected(old('course_id') == $id)>{{ $label }}</option>
            @endforeach
        </select>
        @error('course_id')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Module / Section *</label>
        <input type="text" name="module" value="{{ old('module') }}" class="lms-input" placeholder="Module or section name" required>
        @error('module')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Duration (minutes) *</label>
        <input type="number" name="duration" min="1" step="1" value="{{ old('duration') }}" class="lms-input" placeholder="45" required>
        @error('duration')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Release date *</label>
        <input type="date" name="release_date" value="{{ old('release_date') }}" class="lms-input" required>
        @error('release_date')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Status *</label>
        <select name="status" id="lesson_status" class="lms-input cursor-pointer" required>
            <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status') === 'published')>Published</option>
        </select>
        @error('status')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Thumbnail image</label>
        <input type="file" name="thumbnail" accept="image/*" class="w-full cursor-pointer rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] px-4 py-3 text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-[#E50914] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#ff1a25]">
        <p class="mt-2 text-xs text-white/25">PNG, JPG, or WEBP up to 2MB.</p>
        @error('thumbnail')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Description *</label>
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
            >{!! old('description') !!}</div>
            <textarea name="description" class="hidden" data-editor-input required>{{ old('description') }}</textarea>
        </div>
        @error('description')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Video file</label>
        <input type="file" name="video_file" accept="video/*" class="w-full cursor-pointer rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] px-4 py-3 text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
        <p class="mt-2 text-xs text-white/25">MP4, MOV, WMV, or WebM up to 50MB.</p>
        @error('video_file')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">YouTube / Vimeo URL</label>
        <input type="url" name="video_url" value="{{ old('video_url') }}" class="lms-input" placeholder="https://youtube.com/... or https://vimeo.com/...">
        @error('video_url')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Attachments</label>
        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,image/*" class="w-full cursor-pointer rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] px-4 py-3 text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
        <p class="mt-2 text-xs text-white/25">Upload PDFs, Word files, and images. You can select multiple files.</p>
        @error('attachments')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
        @error('attachments.*')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>
</div>
