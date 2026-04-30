@php
    $questionItems = old('questions', isset($quiz) ? $quiz->questions->map(fn ($question) => [
        'type' => $question->type ?? 'mcq',
        'question' => $question->question,
        'option_one' => $question->option_one,
        'option_two' => $question->option_two,
        'option_three' => $question->option_three,
        'option_four' => $question->option_four,
        'correct_option' => $question->correct_option,
        'correct_answer' => $question->correct_answer,
        'marks' => $question->marks ?? 1,
        'explanation' => $question->explanation,
    ])->values()->all() : [[
        'type' => 'mcq',
        'question' => '',
        'option_one' => '',
        'option_two' => '',
        'option_three' => '',
        'option_four' => '',
        'correct_option' => 0,
        'correct_answer' => null,
        'marks' => 1,
        'explanation' => null,
    ]]);

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

<div class="mt-8" x-data="quizBuilder(@js($questionItems))">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white">Questions</h3>
            <p class="text-sm text-white/35">Add questions (MCQ, True/False, Short Answer, Essay) without reloading.</p>
        </div>
        <button type="button" class="lms-button-secondary" @click="addQuestion()">Add Question</button>
    </div>

    <template x-for="(question, index) in questions" :key="index">
        <div class="mt-6 rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition-all duration-300 hover:border-[#E50914]/20">
            <div class="flex items-center justify-between gap-4">
                <h4 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/30">Question <span x-text="index + 1"></span></h4>
                <button type="button" class="text-sm font-semibold text-[#E50914] hover:text-[#ff4d55] transition" @click="removeQuestion(index)" x-show="questions.length > 1">Remove</button>
            </div>

            <div class="mt-4 grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/80">Type</label>
                            <select :name="`questions[${index}][type]`" x-model="question.type" class="lms-input cursor-pointer">
                                <option value="mcq">Multiple Choice (MCQ)</option>
                                <option value="true_false">True / False</option>
                                <option value="short_answer">Short Answer</option>
                                <option value="essay">Essay</option>
                            </select>
                        </div>
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Question Text *</label>
                    <textarea :name="`questions[${index}][question]`" rows="3" x-model="question.question" class="lms-input" required></textarea>
                </div>


                <!-- MCQ options -->
                <template x-if="question.type === 'mcq'">
                    <div class="lg:col-span-2">
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

                <!-- True/False -->
                <template x-if="question.type === 'true_false'">
                    <div class="lg:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Correct Answer *</label>
                        <select :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" class="lms-input cursor-pointer" required>
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                </template>

                <!-- Short Answer -->
                <template x-if="question.type === 'short_answer'">
                    <div class="lg:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Expected Answer *</label>
                        <input type="text" :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" class="lms-input" required>
                        <p class="mt-2 text-xs text-white/25">Short answer expected from student. Answers will be matched loosely.</p>
                    </div>
                </template>

                <!-- Essay (no correct answer) -->
                <template x-if="question.type === 'essay'">
                    <div class="lg:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-white/80">Notes for graders (optional)</label>
                        <textarea :name="`questions[${index}][correct_answer]`" x-model="question.correct_answer" rows="2" class="lms-input" placeholder="Guidance for graders (optional)"></textarea>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
    function quizBuilder(initialQuestions) {
        return {
            questions: initialQuestions.length ? initialQuestions : [{ type: 'mcq', question: '', option_one: '', option_two: '', option_three: '', option_four: '', correct_option: 0, correct_answer: null, marks: 1, explanation: null }],
            addQuestion() {
                this.questions.push({ type: 'mcq', question: '', option_one: '', option_two: '', option_three: '', option_four: '', correct_option: 0, correct_answer: null, marks: 1, explanation: null });
            },
            removeQuestion(index) {
                this.questions.splice(index, 1);
                if (!this.questions.length) {
                    this.addQuestion();
                }
            },
        };
    }
</script>
