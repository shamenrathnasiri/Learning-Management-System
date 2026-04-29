<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lessons</p>
                <h2 class="text-3xl font-bold text-white">Create lesson</h2>
            </div>
            <p class="max-w-2xl text-sm text-white/40">Build a lesson draft or publish it immediately with media, attachments, and a rich text description.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-sm font-medium text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form id="lesson-create-form" method="POST" action="{{ route('lessons.store') }}" enctype="multipart/form-data" class="lms-card space-y-8 p-6 md:p-8">
            @csrf

            @if ($errors->any())
                <div class="rounded-3xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-100">
                    <p class="font-semibold">Please fix the highlighted fields.</p>
                </div>
            @endif

            @include('lessons._create-form', ['courses' => $courses])

            <div class="gradient-line"></div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('lessons.index') }}" class="lms-button-secondary">Cancel</a>
                <button type="submit" onclick="document.getElementById('lesson_status').value = 'draft';" class="lms-button-secondary">Save draft</button>
                <button type="submit" onclick="document.getElementById('lesson_status').value = 'published';" class="lms-button">Publish lesson</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.tinymce) {
                    tinymce.init({
                        selector: '#lesson_description',
                        height: 320,
                        menubar: false,
                        branding: false,
                        skin: 'oxide-dark',
                        content_css: 'dark',
                        plugins: 'lists link table code preview autoresize',
                        toolbar: 'blocks | undo redo | bold italic underline | bullist numlist | link table | code preview',
                        block_formats: 'Paragraph=p; Section=h2; Subsection=h3; Small heading=h4',
                        forced_root_block: 'p',
                        convert_newlines_to_brs: false,
                        setup: function (editor) {
                            editor.on('change keyup', function () {
                                editor.save();
                            });
                        }
                    });
                }

                const lessonForm = document.getElementById('lesson-create-form');
                if (lessonForm && window.tinymce) {
                    lessonForm.addEventListener('submit', function () {
                        window.tinymce.triggerSave();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
