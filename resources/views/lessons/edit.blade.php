<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Lessons</p>
            <h2 class="text-2xl font-bold text-white">Edit lesson</h2>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('lessons.update', $lesson) }}" enctype="multipart/form-data" class="lms-card space-y-6 p-6">
            @csrf
            @method('PUT')
            @include('lessons._form', ['lesson' => $lesson])
            <div class="gradient-line"></div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('lessons.show', $lesson) }}" class="lms-button-secondary">Cancel</a>
                <button class="lms-button" type="submit">Save changes</button>
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

                const lessonForm = document.querySelector('form[action="{{ route('lessons.update', $lesson) }}"]');
                if (lessonForm && window.tinymce) {
                    lessonForm.addEventListener('submit', function () {
                        window.tinymce.triggerSave();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
