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
        <p class="mt-2 text-xs text-white/25">Optional. PNG, JPG, or WEBP up to 2MB.</p>
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

    <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-white/[0.03] p-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-white">Live class schedule</h3>
                <p class="mt-1 text-xs text-white/35">Add a Zoom or Google Meet session for this lesson.</p>
            </div>
            <span class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff8088]">Optional</span>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Provider</label>
                <select name="live_class_provider" class="lms-input cursor-pointer">
                    <option value="" @selected(old('live_class_provider') === null || old('live_class_provider') === '')>No live class</option>
                    <option value="zoom" @selected(old('live_class_provider') === 'zoom')>Zoom</option>
                    <option value="google_meet" @selected(old('live_class_provider') === 'google_meet')>Google Meet</option>
                </select>
                @error('live_class_provider')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Meeting title</label>
                <input type="text" name="live_class_title" value="{{ old('live_class_title') }}" class="lms-input" placeholder="Weekly live class or revision session">
                @error('live_class_title')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Start date & time</label>
                <input type="datetime-local" name="live_class_start_at" value="{{ old('live_class_start_at') }}" class="lms-input">
                @error('live_class_start_at')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Duration (minutes)</label>
                <input type="number" name="live_class_duration" min="1" max="1440" step="1" value="{{ old('live_class_duration') }}" class="lms-input" placeholder="60">
                @error('live_class_duration')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Meeting URL</label>
                <input type="url" name="live_class_meeting_url" value="{{ old('live_class_meeting_url') }}" class="lms-input" placeholder="https://zoom.us/j/... or https://meet.google.com/...">
                <p class="mt-2 text-xs text-white/25">Paste the Zoom or Google Meet join link here.</p>
                @error('live_class_meeting_url')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Meeting code / passcode</label>
                <input type="text" name="live_class_meeting_code" value="{{ old('live_class_meeting_code') }}" class="lms-input" placeholder="123-456-789 or ABCD1234">
                @error('live_class_meeting_code')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-white/80">Passcode</label>
                <input type="text" name="live_class_passcode" value="{{ old('live_class_passcode') }}" class="lms-input" placeholder="Optional meeting passcode">
                @error('live_class_passcode')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Video file</label>
        <input type="file" name="video_file" accept="video/*" class="w-full cursor-pointer rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] px-4 py-3 text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
        <p class="mt-2 text-xs text-white/25">Optional. MP4, MOV, WMV, or WebM up to 50MB.</p>
        @error('video_file')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">YouTube / Vimeo URL</label>
        <input type="url" name="video_url" value="{{ old('video_url') }}" class="lms-input" placeholder="https://youtube.com/... or https://vimeo.com/...">
        <p class="mt-2 text-xs text-white/25">Optional. Use this if you do not upload a video file.</p>
        @error('video_url')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Attachments</label>
        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,image/*" class="w-full cursor-pointer rounded-2xl border-2 border-dashed border-white/15 bg-white/[0.03] px-4 py-3 text-sm text-white/60 file:mr-3 file:rounded-lg file:border-0 file:bg-white/10 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
        <p class="mt-2 text-xs text-white/25">Optional. Upload PDFs, Word files, and images. You can select multiple files.</p>
        @error('attachments')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
        @error('attachments.*')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>
</div>
