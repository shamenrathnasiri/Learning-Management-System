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

    <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-white/[0.03] p-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-white">Live class schedule</h3>
                <p class="mt-1 text-xs text-white/35">Attach a Zoom or Google Meet session to this lesson.</p>
            </div>
            <span class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff8088]">Optional</span>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Provider</label>
                <select name="live_class_provider" class="lms-input cursor-pointer">
                    <option value="" @selected(old('live_class_provider', $lesson->live_class_provider ?? '') === '')>No live class</option>
                    <option value="zoom" @selected(old('live_class_provider', $lesson->live_class_provider ?? '') === 'zoom')>Zoom</option>
                    <option value="google_meet" @selected(old('live_class_provider', $lesson->live_class_provider ?? '') === 'google_meet')>Google Meet</option>
                </select>
                @error('live_class_provider')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Meeting title</label>
                <input type="text" name="live_class_title" value="{{ old('live_class_title', $lesson->live_class_title ?? '') }}" class="lms-input" placeholder="Weekly live class or revision session">
                @error('live_class_title')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Start date & time</label>
                <input type="datetime-local" name="live_class_start_at" value="{{ old('live_class_start_at', optional($lesson->live_class_start_at ?? null)->format('Y-m-d\TH:i')) }}" class="lms-input">
                @error('live_class_start_at')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Duration (minutes)</label>
                <input type="number" name="live_class_duration" min="1" max="1440" step="1" value="{{ old('live_class_duration', $lesson->live_class_duration ?? '') }}" class="lms-input" placeholder="60">
                @error('live_class_duration')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Meeting URL</label>
                <input type="url" name="live_class_meeting_url" value="{{ old('live_class_meeting_url', $lesson->live_class_meeting_url ?? '') }}" class="lms-input" placeholder="https://zoom.us/j/... or https://meet.google.com/...">
                <p class="mt-2 text-xs text-white/25">Paste the Zoom or Google Meet join link here.</p>
                @error('live_class_meeting_url')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white/80 mb-2">Meeting code / passcode</label>
                <input type="text" name="live_class_meeting_code" value="{{ old('live_class_meeting_code', $lesson->live_class_meeting_code ?? '') }}" class="lms-input" placeholder="123-456-789 or ABCD1234">
                @error('live_class_meeting_code')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-white/80 mb-2">Passcode</label>
                <input type="text" name="live_class_passcode" value="{{ old('live_class_passcode', $lesson->live_class_passcode ?? '') }}" class="lms-input" placeholder="Optional meeting passcode">
                @error('live_class_passcode')<p class="mt-2 text-xs text-[#E50914] font-semibold">{{ $message }}</p>@enderror
            </div>
        </div>
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
