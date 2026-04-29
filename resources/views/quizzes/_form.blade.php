@php
    $questionItems = old('questions', isset($quiz) ? $quiz->questions->map(fn ($question) => [
        'question' => $question->question,
        'option_one' => $question->option_one,
        'option_two' => $question->option_two,
        'option_three' => $question->option_three,
        'option_four' => $question->option_four,
        'correct_option' => $question->correct_option,
    ])->values()->all() : [[
        'question' => '',
        'option_one' => '',
        'option_two' => '',
        'option_three' => '',
        'option_four' => '',
        'correct_option' => 0,
    ]]);
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Quiz Title *</label>
        <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" class="lms-input" placeholder="Enter quiz title" required>
        @error('title')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-white/80">Passing Score *</label>
        <input type="number" name="passing_score" min="0" max="100" value="{{ old('passing_score', $quiz->passing_score ?? 70) }}" class="lms-input" required>
        @error('passing_score')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>

    <div class="lg:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-white/80">Description</label>
        <textarea name="description" rows="3" class="lms-input" placeholder="Describe the quiz scope and expectations">{{ old('description', $quiz->description ?? '') }}</textarea>
        @error('description')<p class="mt-2 text-xs font-semibold text-[#E50914]">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-8" x-data="quizBuilder(@js($questionItems))">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white">Questions</h3>
            <p class="text-sm text-white/35">Add multiple-choice questions and mark the correct answer.</p>
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
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Question Text *</label>
                    <textarea :name="`questions[${index}][question]`" rows="3" x-model="question.question" class="lms-input" required></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Option A *</label>
                    <input type="text" :name="`questions[${index}][option_one]`" x-model="question.option_one" class="lms-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Option B *</label>
                    <input type="text" :name="`questions[${index}][option_two]`" x-model="question.option_two" class="lms-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Option C *</label>
                    <input type="text" :name="`questions[${index}][option_three]`" x-model="question.option_three" class="lms-input" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-white/80">Option D *</label>
                    <input type="text" :name="`questions[${index}][option_four]`" x-model="question.option_four" class="lms-input" required>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-white/80">Correct Answer *</label>
                    <select :name="`questions[${index}][correct_option]`" x-model="question.correct_option" class="lms-input cursor-pointer" required>
                        <option value="0">Option A</option>
                        <option value="1">Option B</option>
                        <option value="2">Option C</option>
                        <option value="3">Option D</option>
                    </select>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function quizBuilder(initialQuestions) {
        return {
            questions: initialQuestions.length ? initialQuestions : [{ question: '', option_one: '', option_two: '', option_three: '', option_four: '', correct_option: 0 }],
            addQuestion() {
                this.questions.push({ question: '', option_one: '', option_two: '', option_three: '', option_four: '', correct_option: 0 });
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
