@php
    $normalizeTags = function ($tags): array {
        if (is_array($tags)) {
            return array_values(array_filter(array_map(static fn ($tag) => trim((string) $tag), $tags)));
        }

        if (is_string($tags) && trim($tags) !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $tags))));
        }

        return [];
    };

    $questionItems = collect(old('questions', isset($quiz) ? $quiz->questions->values()->map(fn ($question) => [
        'uid' => (string) \Illuminate\Support\Str::uuid(),
        'type' => $question->type ?? 'mcq',
        'question' => $question->question,
        'difficulty' => $question->difficulty ?? 'medium',
        'tags' => $question->tags ?? [],
        'option_one' => $question->option_one,
        'option_two' => $question->option_two,
        'option_three' => $question->option_three,
        'option_four' => $question->option_four,
        'correct_option' => $question->correct_option,
        'correct_answer' => $question->correct_answer,
        'marks' => $question->marks ?? 1,
        'explanation' => $question->explanation,
        'media_path' => $question->media_path,
        'media_type' => $question->media_type,
        'media_name' => $question->media_name,
    ])->all() : [[
        'uid' => (string) \Illuminate\Support\Str::uuid(),
        'type' => 'mcq',
        'question' => '',
        'difficulty' => 'medium',
        'tags' => [],
        'option_one' => '',
        'option_two' => '',
        'option_three' => '',
        'option_four' => '',
        'correct_option' => 0,
        'correct_answer' => null,
        'marks' => 1,
        'explanation' => null,
        'media_path' => null,
        'media_type' => null,
        'media_name' => null,
    ]]))
        ->values()
        ->map(function ($question) use ($normalizeTags) {
            return [
                'uid' => data_get($question, 'uid', (string) \Illuminate\Support\Str::uuid()),
                'type' => data_get($question, 'type', 'mcq'),
                'question' => data_get($question, 'question', ''),
                'difficulty' => data_get($question, 'difficulty', 'medium'),
                'tags' => $normalizeTags(data_get($question, 'tags')),
                'tagInput' => '',
                'option_one' => data_get($question, 'option_one', ''),
                'option_two' => data_get($question, 'option_two', ''),
                'option_three' => data_get($question, 'option_three', ''),
                'option_four' => data_get($question, 'option_four', ''),
                'correct_option' => data_get($question, 'correct_option', 0),
                'correct_answer' => data_get($question, 'correct_answer'),
                'marks' => data_get($question, 'marks', 1),
                'explanation' => data_get($question, 'explanation'),
                'media_path' => data_get($question, 'media_path'),
                'media_type' => data_get($question, 'media_type'),
                'media_name' => data_get($question, 'media_name'),
            ];
        })
        ->all();

    $questionMediaBaseUrl = rtrim(asset('storage'), '/').'/';

    $courseOptions = $courses ?? [
        1 => 'Computer Science',
        2 => 'Mathematics',
        3 => 'Web Development',
        4 => 'Data Science',
    ];

    $lessonOptions = $lessons ?? collect();
    $selectedCourseId = old('course_id', $quiz->course_id ?? (isset($lesson) ? $lesson->course_id : ''));
    $selectedLessonId = old('lesson_id', $quiz->lesson_id ?? (isset($lesson) ? $lesson->id : ''));
    $assignmentLesson = isset($lesson) ? $lesson : null;
    $resultVisibility = old('result_visibility', $quiz->result_visibility ?? 'immediate');
    $maxAttempts = old('max_attempts', $quiz->max_attempts ?? '');
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Quiz Title *</label>
        <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" class="lms-input" placeholder="Enter quiz title" required>
        @error('title')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Passing Percentage *</label>
        <input type="number" name="passing_score" min="0" max="100" value="{{ old('passing_score', $quiz->passing_score ?? 70) }}" class="lms-input" required>
        @error('passing_score')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Total Marks *</label>
        <input type="number" name="total_marks" min="1" step="1" value="{{ old('total_marks', $quiz->total_marks ?? 100) }}" class="lms-input" required>
        @error('total_marks')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Time Limit (minutes)</label>
        <input type="number" name="time_limit_minutes" min="1" max="1440" step="1" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes ?? '') }}" class="lms-input" placeholder="60">
        <p class="mt-2 text-xs text-white/25">Optional. Leave empty for no time limit.</p>
        @error('time_limit_minutes')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Description</label>
        <textarea name="description" rows="3" class="lms-input" placeholder="Describe the quiz scope and expectations">{{ old('description', $quiz->description ?? '') }}</textarea>
        @error('description')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Instructions</label>
        <textarea name="instructions" rows="4" class="lms-input" placeholder="Add guidance, rules, or notes for students">{{ old('instructions', $quiz->instructions ?? '') }}</textarea>
        @error('instructions')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-8 rounded-3xl border border-white/10 bg-white/[0.03] p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white">Quiz Settings</h3>
            <p class="text-sm text-white/35">Configure randomized delivery, attempts, and post-submission visibility.</p>
        </div>
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.02] p-4">
            <input type="checkbox" name="shuffle_questions" value="1" class="mt-1 rounded border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" @checked(old('shuffle_questions', $quiz->shuffle_questions ?? false))>
            <span>
                <span class="block text-sm font-semibold text-white/80">Shuffle question order</span>
                <span class="mt-1 block text-xs text-white/35">Each attempt can present questions in a different sequence.</span>
            </span>
        </label>

        <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.02] p-4">
            <input type="checkbox" name="shuffle_answers" value="1" class="mt-1 rounded border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" @checked(old('shuffle_answers', $quiz->shuffle_answers ?? false))>
            <span>
                <span class="block text-sm font-semibold text-white/80">Shuffle answer options</span>
                <span class="mt-1 block text-xs text-white/35">Randomizes option placement for each question.</span>
            </span>
        </label>

        <div>
            <label class="mb-2 block text-sm font-semibold text-white/80">Max Attempts</label>
            <input type="number" name="max_attempts" min="1" max="100" step="1" value="{{ $maxAttempts }}" class="lms-input" placeholder="Leave empty for unlimited">
            <p class="mt-2 text-xs text-white/25">Leave empty to allow unlimited attempts.</p>
            @error('max_attempts')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-white/80">Result Visibility</label>
            <select name="result_visibility" class="lms-input cursor-pointer" required>
                <option value="immediate" @selected($resultVisibility === 'immediate')>Immediately after each submission</option>
                <option value="after_last_attempt" @selected($resultVisibility === 'after_last_attempt')>Only after final allowed attempt</option>
                <option value="hidden" @selected($resultVisibility === 'hidden')>Do not show to students</option>
            </select>
            <p class="mt-2 text-xs text-white/25">Tutors and admins can always review attempts.</p>
            @error('result_visibility')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.02] p-4">
            <input type="checkbox" name="show_correct_answers" value="1" class="mt-1 rounded border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" @checked(old('show_correct_answers', $quiz->show_correct_answers ?? true))>
            <span>
                <span class="block text-sm font-semibold text-white/80">Show correct answers after submission</span>
                <span class="mt-1 block text-xs text-white/35">When results are visible, include answer keys for students.</span>
            </span>
        </label>

        <label class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.02] p-4">
            <input type="checkbox" name="show_explanations" value="1" class="mt-1 rounded border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" @checked(old('show_explanations', $quiz->show_explanations ?? true))>
            <span>
                <span class="block text-sm font-semibold text-white/80">Show explanations after submission</span>
                <span class="mt-1 block text-xs text-white/35">Reveals question explanations on the result page.</span>
            </span>
        </label>
    </div>
</div>

<div class="mt-8 rounded-3xl border border-white/10 bg-white/[0.03] p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white">Assignment</h3>
            <p class="text-sm text-white/35">Assign this quiz to a course, a lesson, or both.</p>
        </div>
        @if ($assignmentLesson)
            <span class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff8088]">Lesson preset</span>
        @endif
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-white/80">Course</label>
            <select name="course_id" class="lms-input cursor-pointer">
                <option value="">Select course...</option>
                @foreach ($courseOptions as $id => $label)
                    <option value="{{ $id }}" @selected((string) $selectedCourseId === (string) $id)>{{ $label }}</option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-white/25">Optional if you select a lesson.</p>
            @error('course_id')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
        </div>

        @if ($assignmentLesson)
            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Lesson</label>
                <input type="hidden" name="lesson_id" value="{{ $assignmentLesson->id }}">
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/70">
                    {{ $assignmentLesson->title }}
                </div>
                <p class="mt-2 text-xs text-white/25">This quiz is linked to the selected lesson.</p>
                @error('lesson_id')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>
        @else
            <div>
                <label class="mb-2 block text-sm font-semibold text-white/80">Lesson</label>
                <select name="lesson_id" class="lms-input cursor-pointer">
                    <option value="">Select lesson...</option>
                    @foreach ($lessonOptions as $item)
                        <option value="{{ $item->id }}" @selected((string) $selectedLessonId === (string) $item->id)>{{ $item->title }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs text-white/25">Optional if you want a course-level quiz.</p>
                @error('lesson_id')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
            </div>
        @endif
    </div>
</div>

<div class="mt-8" x-data="quizBuilder(@js($questionItems), @js($questionMediaBaseUrl))">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white">Questions</h3>
            <p class="text-sm text-white/35">Add, duplicate, tag, upload media, and reorder questions without reloading.</p>
        </div>
        <button type="button" class="lms-button-secondary" @click="addQuestion()">Add Question</button>
    </div>

    <template x-for="(question, index) in questions" :key="question.uid">
        <div class="mt-6 rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:border-[#E50914]/20" :class="draggingIndex === index ? 'border-[#E50914]/40 bg-[#E50914]/5' : ''" draggable="true" @dragstart="dragStart(index)" @dragover.prevent @drop.prevent="dropQuestion(index)" @dragend="dragEnd()">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/[0.02] text-white/50 transition hover:border-[#E50914]/30 hover:text-white" title="Drag to reorder" @mousedown.prevent>
                        <span class="text-lg leading-none">⋮⋮</span>
                    </button>
                    <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/30">Question <span x-text="index + 1"></span></h4>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/50 transition hover:border-white/20 hover:text-white" @click="duplicateQuestion(index)">Duplicate</button>
                    <button type="button" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/50 transition hover:border-white/20 hover:text-white" @click="moveQuestion(index, -1)" :disabled="index === 0">Up</button>
                    <button type="button" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white/50 transition hover:border-white/20 hover:text-white" @click="moveQuestion(index, 1)" :disabled="index === questions.length - 1">Down</button>
                    <button type="button" class="text-sm font-semibold text-[#E50914] transition hover:text-[#ff4d55]" @click="removeQuestion(index)" x-show="questions.length > 1">Remove</button>
                </div>
            </div>

            <input type="hidden" :name="`questions[${index}][existing_media_path]`" :value="question.media_path ?? ''">
            <input type="hidden" :name="`questions[${index}][existing_media_type]`" :value="question.media_type ?? ''">
            <input type="hidden" :name="`questions[${index}][existing_media_name]`" :value="question.media_name ?? ''">
            <input type="hidden" :name="`questions[${index}][tags]`" :value="question.tags.join(',')">

            <div class="mt-4 grid gap-4 lg:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Type</label>
                    <select :name="`questions[${index}][type]`" x-model="question.type" class="lms-input cursor-pointer">
                        <option value="mcq">Multiple Choice (MCQ)</option>
                        <option value="true_false">True / False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Difficulty</label>
                    <select :name="`questions[${index}][difficulty]`" x-model="question.difficulty" class="lms-input cursor-pointer">
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Marks</label>
                    <input type="number" min="0" step="1" :name="`questions[${index}][marks]`" x-model="question.marks" class="lms-input">
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Question Text *</label>
                    <textarea :name="`questions[${index}][question]`" rows="3" x-model="question.question" class="lms-input" required></textarea>
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Explanation / feedback</label>
                    <textarea :name="`questions[${index}][explanation]`" rows="2" x-model="question.explanation" class="lms-input" placeholder="Optional hint, solution note, or marking guidance"></textarea>
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Media</label>
                    <input type="file" :name="`questions[${index}][media]`" class="lms-input cursor-pointer" accept="image/*,video/*,.pdf,.doc,.docx,.txt,.ppt,.pptx">
                    <p class="mt-2 text-xs text-white/25">Optional image, video, or file for the question prompt.</p>
                    <div class="mt-3 rounded-2xl border border-white/10 bg-black/20 p-3" x-show="question.media_path" x-cloak>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/30">Current media</p>
                        <template x-if="question.media_type && question.media_type.startsWith('image/')">
                            <img :src="mediaUrl(question.media_path)" class="mt-3 max-h-56 rounded-2xl border border-white/10 object-cover" alt="Question media preview">
                        </template>
                        <template x-if="question.media_type && question.media_type.startsWith('video/')">
                            <video class="mt-3 w-full rounded-2xl border border-white/10" controls>
                                <source :src="mediaUrl(question.media_path)" :type="question.media_type">
                            </video>
                        </template>
                        <template x-if="!question.media_type || (!question.media_type.startsWith('image/') && !question.media_type.startsWith('video/'))">
                            <a :href="mediaUrl(question.media_path)" target="_blank" class="mt-3 inline-flex items-center rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-white/70 transition hover:border-[#E50914]/30 hover:text-white">
                                <span x-text="question.media_name || 'Open attachment'"></span>
                            </a>
                        </template>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Tags</label>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-4">
                        <div class="flex flex-wrap gap-2" x-show="question.tags.length">
                            <template x-for="tag in question.tags" :key="tag">
                                <span class="inline-flex items-center gap-2 rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-3 py-1 text-xs font-semibold text-[#ff8088]">
                                    <span x-text="tag"></span>
                                    <button type="button" class="text-[#ff8088] transition hover:text-white" @click="removeTag(index, tag)">×</button>
                                </span>
                            </template>
                        </div>
                        <div class="mt-3 flex flex-col gap-3 sm:flex-row">
                            <input type="text" x-model="question.tagInput" class="lms-input flex-1" placeholder="Add a tag and press Enter" @keydown.enter.prevent="addTag(index)">
                            <button type="button" class="lms-button-secondary sm:w-auto" @click="addTag(index)">Add tag</button>
                        </div>
                    </div>
                </div>

                <template x-if="question.type === 'mcq'">
                    <div class="lg:col-span-3">
                        <div class="grid gap-4 lg:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-white/80">Option A *</label>
                                <input type="text" :name="`questions[${index}][option_one]`" x-model="question.option_one" class="lms-input" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-white/80">Option B *</label>
                                <input type="text" :name="`questions[${index}][option_two]`" x-model="question.option_two" class="lms-input" required>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-white/80">Option C</label>
                                <input type="text" :name="`questions[${index}][option_three]`" x-model="question.option_three" class="lms-input">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-white/80">Option D</label>
                                <input type="text" :name="`questions[${index}][option_four]`" x-model="question.option_four" class="lms-input">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="mb-2 block text-sm font-semibold text-white/80">Correct Answer *</label>
                            <select :name="`questions[${index}][correct_option]`" x-model="question.correct_option" class="lms-input cursor-pointer" required>
                                <option value="0">Option A</option>
                                <option value="1">Option B</option>
                                <option value="2">Option C</option>
                                <option value="3">Option D</option>
                            </select>
                        </div>
                    </div>
                </template>

                <template x-if="question.type === 'true_false'">
                    <div class="lg:col-span-3">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Correct Answer *</label>
                        <select :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" class="lms-input cursor-pointer" required>
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                </template>

                <template x-if="question.type === 'short_answer'">
                    <div class="lg:col-span-3">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Expected Answer *</label>
                        <input type="text" :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" class="lms-input" required>
                        <p class="mt-2 text-xs text-white/25">Short answer expected from student. Answers will be matched loosely.</p>
                    </div>
                </template>

                <template x-if="question.type === 'essay'">
                    <div class="lg:col-span-3">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Notes for graders (optional)</label>
                        <textarea :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" rows="2" class="lms-input" placeholder="Guidance for graders (optional)"></textarea>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
    function quizBuilder(initialQuestions, mediaBaseUrl) {
        return {
            questions: [],
            mediaBaseUrl,
            draggingIndex: null,
            init() {
                const fallbackQuestions = [{
                    type: 'mcq',
                    question: '',
                    difficulty: 'medium',
                    tags: [],
                    option_one: '',
                    option_two: '',
                    option_three: '',
                    option_four: '',
                    correct_option: 0,
                    correct_answer: null,
                    marks: 1,
                    explanation: null,
                    media_path: null,
                    media_type: null,
                    media_name: null,
                }];

                this.questions = (initialQuestions.length ? initialQuestions : fallbackQuestions).map((question) => this.normalizeQuestion(question));
            },
            normalizeQuestion(question) {
                return {
                    uid: question.uid ?? crypto.randomUUID(),
                    type: question.type ?? 'mcq',
                    question: question.question ?? '',
                    difficulty: question.difficulty ?? 'medium',
                    tags: Array.isArray(question.tags)
                        ? question.tags
                        : String(question.tags ?? '').split(',').map((tag) => tag.trim()).filter(Boolean),
                    tagInput: '',
                    option_one: question.option_one ?? '',
                    option_two: question.option_two ?? '',
                    option_three: question.option_three ?? '',
                    option_four: question.option_four ?? '',
                    correct_option: question.correct_option ?? 0,
                    correct_answer: question.correct_answer ?? null,
                    marks: question.marks ?? 1,
                    explanation: question.explanation ?? null,
                    media_path: question.media_path ?? null,
                    media_type: question.media_type ?? null,
                    media_name: question.media_name ?? null,
                };
            },
            addQuestion() {
                this.questions.push(this.normalizeQuestion({
                    type: 'mcq',
                    question: '',
                    difficulty: 'medium',
                    tags: [],
                    option_one: '',
                    option_two: '',
                    option_three: '',
                    option_four: '',
                    correct_option: 0,
                    correct_answer: null,
                    marks: 1,
                    explanation: null,
                    media_path: null,
                    media_type: null,
                    media_name: null,
                }));
            },
            removeQuestion(index) {
                this.questions.splice(index, 1);

                if (!this.questions.length) {
                    this.addQuestion();
                }
            },
            duplicateQuestion(index) {
                const copy = JSON.parse(JSON.stringify(this.questions[index]));

                copy.uid = crypto.randomUUID();
                copy.question = copy.question ? `${copy.question} (copy)` : '(copy)';
                copy.tagInput = '';

                this.questions.splice(index + 1, 0, copy);
            },
            moveQuestion(index, offset) {
                const targetIndex = index + offset;

                if (targetIndex < 0 || targetIndex >= this.questions.length) {
                    return;
                }

                const [item] = this.questions.splice(index, 1);
                this.questions.splice(targetIndex, 0, item);
            },
            dragStart(index) {
                this.draggingIndex = index;
            },
            dropQuestion(index) {
                if (this.draggingIndex === null || this.draggingIndex === index) {
                    this.draggingIndex = null;
                    return;
                }

                const [item] = this.questions.splice(this.draggingIndex, 1);
                this.questions.splice(index, 0, item);
                this.draggingIndex = null;
            },
            dragEnd() {
                this.draggingIndex = null;
            },
            addTag(index) {
                const question = this.questions[index];
                const tag = (question.tagInput || '').trim();

                if (!tag || question.tags.includes(tag)) {
                    question.tagInput = '';
                    return;
                }

                question.tags.push(tag);
                question.tagInput = '';
            },
            removeTag(index, tag) {
                const question = this.questions[index];
                question.tags = question.tags.filter((item) => item !== tag);
            },
            mediaUrl(path) {
                return path ? `${this.mediaBaseUrl}${path}` : '';
            },
        };
    }
</script>