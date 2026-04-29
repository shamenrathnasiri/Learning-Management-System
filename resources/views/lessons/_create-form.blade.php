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
        <textarea id="lesson_description" name="description" rows="10" class="lms-input min-h-[240px]" placeholder="Write the lesson content here..." required>{{ old('description') }}</textarea>
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
