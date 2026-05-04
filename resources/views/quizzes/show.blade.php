<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#E50914]">Quiz</p>
                <h2 class="text-2xl font-bold text-white">{{ $quiz->title }}</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($quiz->lesson)
                    <a href="{{ route('lessons.show', $quiz->lesson) }}" class="lms-button-secondary">Back to lesson</a>
                @else
                    <a href="{{ route('quizzes.create') }}" class="lms-button-secondary">Back to quiz hub</a>
                @endif
                @if(auth()->user()->isTutor() || auth()->user()->isAdministrator())
                    @if (! $quiz->is_published)
                        <form method="POST" action="{{ route('quizzes.publish', $quiz) }}">
                            @csrf
                            <button type="submit" class="lms-button">Publish quiz</button>
                        </form>
                    @endif
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="lms-button-secondary">Edit quiz</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('quizzes.attempt.store', $quiz) }}" class="lms-card space-y-6 p-6" id="quiz-attempt-form" data-attempt-active="{{ (! $attemptLimitReached && ! $accessDenied) ? '1' : '0' }}">
            @csrf
            <input type="hidden" name="attempt_started_at" value="{{ $attemptStartedAt }}">

            {{-- Quiz info header --}}
            <div class="rounded-3xl border border-[#E50914]/20 bg-[#E50914]/10 p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-[#E50914]/60">Assignment</p>
                <h3 class="mt-3 text-2xl font-bold text-white">{{ $quiz->lesson->title ?? ($quiz->course_id ? 'Course '.$quiz->course_id : 'Unassigned quiz') }}</h3>
                <p class="mt-2 text-sm text-white/50">Passing percentage: {{ $quiz->passing_score }}%</p>
                @if ($quiz->time_limit_minutes)
                    <p class="mt-2 text-sm text-white/50">Time limit: {{ $quiz->time_limit_minutes }} minutes</p>
                @endif
                @if ($quiz->total_marks)
                    <p class="mt-2 text-sm text-white/50">Total marks: {{ $quiz->total_marks }}</p>
                @endif
                @if ($quiz->max_attempts)
                    <p class="mt-2 text-sm text-white/50">Attempts used: {{ $attemptCount }}/{{ $quiz->max_attempts }}</p>
                @endif
                @if ($quiz->starts_at)
                    <p class="mt-2 text-sm text-white/50">Starts: {{ $quiz->starts_at->format('M j, Y g:i A') }}</p>
                @endif
                @if ($quiz->ends_at)
                    <p class="mt-2 text-sm text-white/50">Ends: {{ $quiz->ends_at->format('M j, Y g:i A') }}</p>
                @endif
                @if ($quiz->restrict_to_enrolled_students)
                    <p class="mt-2 text-sm text-white/50">Access: Enrolled students only</p>
                @endif
                <p class="mt-2 text-sm text-white/50">
                    Status:
                    @if ($quiz->is_published)
                        <span class="font-semibold text-emerald-300">Live</span>
                    @else
                        <span class="font-semibold text-amber-300">Draft</span>
                    @endif
                </p>
                @if ($quiz->description)
                    <p class="mt-4 text-sm text-white/60">{{ $quiz->description }}</p>
                @endif
                @if ($quiz->instructions)
                    <div class="mt-4 rounded-2xl border border-white/10 bg-black/20 p-4 text-sm text-white/60">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/30">Instructions</p>
                        <p class="mt-2 whitespace-pre-line">{{ $quiz->instructions }}</p>
                    </div>
                @endif
            </div>

            @error('attempts')
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    {{ $message }}
                </div>
            @enderror

            @error('access')
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    {{ $message }}
                </div>
            @enderror

            @error('timer')
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    {{ $message }}
                </div>
            @enderror

            @if ($attemptLimitReached)
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    You have reached the maximum number of attempts for this quiz.
                </div>
            @endif

            @if ($startsInFuture)
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    This quiz is not open yet. It becomes available on {{ $quiz->starts_at?->format('M j, Y g:i A') }}.
                </div>
            @endif

            @if ($ended)
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    This quiz has closed and no longer accepts submissions.
                </div>
            @endif

            @if (! $isEnrolled)
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-3 text-sm font-semibold text-[#ff9ea3]">
                    You must be enrolled in the lesson to attempt this quiz.
                </div>
            @endif

            @if ($isUnpublished)
                <div class="rounded-2xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 text-sm font-semibold text-amber-200">
                    This quiz is currently in draft mode and is not live for students yet.
                </div>
            @endif

            @if ($quiz->time_limit_minutes && ! $accessDenied && ! $attemptLimitReached)
                <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-4" id="quiz-timer-box" data-time-limit-minutes="{{ $quiz->time_limit_minutes }}" data-auto-submit="{{ $quiz->auto_submit_on_expiry ? '1' : '0' }}">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white/20 bg-white/5">
                                <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="text-sm text-white/70">
                                Time remaining: <span class="font-bold text-white text-base" id="quiz-timer-display">--:--</span>
                            </div>
                        </div>
                        @if ($quiz->auto_submit_on_expiry)
                            <span class="inline-block rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-1 text-xs font-semibold text-emerald-300">Auto-submit when time expires</span>
                        @endif
                    </div>
                    <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-white/5">
                        <div id="quiz-timer-bar" class="h-full w-full rounded-full bg-emerald-500 transition-all duration-300" style="width: 100%"></div>
                    </div>
                </div>
            @endif

            @if (! $accessDenied && ! $attemptLimitReached)
                <div class="rounded-2xl border border-[#E50914]/30 bg-[#E50914]/10 px-4 py-4" id="exam-guard-panel">
                    <div class="flex items-start gap-3">
                        <svg class="mt-1 h-5 w-5 flex-shrink-0 text-[#E50914]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zm-2-4a1 1 0 100-2 1 1 0 000 2zM5.3 13.6A3 3 0 0110 12c1.4 0 2.7.5 3.7 1.4A4.97 4.97 0 0010 15c-2 0-3.8-.8-5.1-2.1.8.7 1.7 1.2 2.8 1.4zM8 12a1 1 0 100-2 1 1 0 000 2zm-1.9 5.5A1 1 0 0010 15a1 1 0 001 1h.1A8 8 0 007.4 18.5zM3 1h4a1 1 0 011 1v4H2V2a1 1 0 011-1z"></path></svg>
                        <div class="flex-1">
                            <p class="font-semibold text-white">Exam mode required</p>
                            <p class="mt-1 text-xs text-white/70">This quiz requires fullscreen mode, blocks copy/paste and shortcuts, monitors tab switching, and enforces forward-only navigation for academic integrity.</p>
                            <button type="button" id="enter-exam-mode-btn" class="lms-button mt-3" aria-label="Start quiz in fullscreen mode">
                                <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 20v-4m0 4h4m-4-4l5-5m11 5v-4m0 4h-4m4-4l-5-5"></path></svg>
                                Start in fullscreen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="hidden rounded-2xl border border-amber-400/30 bg-amber-400/10 px-4 py-3 text-sm text-amber-200" id="tab-warning-box" role="alert">
                    <p class="font-semibold">⚠️ Tab switching detected</p>
                    <p class="mt-1 text-xs">Warning <span id="tab-warning-count">0</span>/3 - Excessive tab switching may result in quiz termination.</p>
                </div>
            @endif

            {{-- Questions Progress Bar --}}
            @if (! $accessDenied && ! $attemptLimitReached)
                <div class="space-y-2" id="progress-section">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em]">
                        <span class="text-white/60">Progress</span>
                        <span class="text-white/40" id="progress-text">0 of {{ count($presentedQuestions) }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-white/5">
                        <div id="progress-bar" class="h-full w-0 rounded-full bg-gradient-to-r from-[#E50914] to-emerald-500 transition-all duration-300" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($presentedQuestions as $index => $item)
                            <button type="button" class="question-dot h-2 w-2 flex-shrink-0 rounded-full border border-white/20 bg-white/5 transition-all hover:bg-white/10" data-question-index="{{ $index }}" aria-label="Question {{ $index + 1 }}" aria-current="false"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Questions --}}
            @foreach ($presentedQuestions as $index => $item)
                @php($question = $item['question'])
                <div class="exam-question rounded-3xl border border-white/10 bg-white/[0.03] p-5 transition hover:border-white/20" data-question-index="{{ $index }}" data-question-type="{{ $question->type }}">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/30 mb-2">
                        <span class="rounded-full border border-white/10 bg-white/[0.03] px-2 py-1">{{ ucfirst($question->difficulty ?? 'medium') }}</span>
                        <span class="rounded-full border border-white/10 bg-white/[0.03] px-2 py-1">{{ ucwords(str_replace('_', ' ', $question->type)) }}</span>
                        @foreach (($question->tags ?? []) as $tag)
                            <span class="rounded-full border border-[#E50914]/20 bg-[#E50914]/10 px-2 py-1 text-[#ff8088]">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <h4 class="mt-3 text-lg font-semibold text-white">{{ $question->question }}</h4>
                    @if ($question->media_path)
                        <div class="mt-4 rounded-2xl border border-white/10 bg-black/20 p-3">
                            @if (str_starts_with($question->media_type ?? '', 'image/'))
                                <img src="{{ asset('storage/'.$question->media_path) }}" alt="Question media" class="max-h-80 rounded-2xl border border-white/10 object-cover">
                            @elseif (str_starts_with($question->media_type ?? '', 'video/'))
                                <video controls class="w-full rounded-2xl border border-white/10">
                                    <source src="{{ asset('storage/'.$question->media_path) }}" type="{{ $question->media_type }}">
                                </video>
                            @else
                                <a href="{{ asset('storage/'.$question->media_path) }}" target="_blank" class="inline-flex items-center rounded-full border border-white/10 px-3 py-1 text-sm font-semibold text-white/70 transition hover:border-[#E50914]/30 hover:text-white">{{ $question->media_name ?? 'Open attachment' }}</a>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4">
                        @if (in_array($question->type, ['multiple_choice', 'true_false']))
                            {{-- MCQ/True-False Options --}}
                            <div class="space-y-3 text-sm">
                                @foreach ($item['options'] as $option)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.02] px-4 py-3 text-white/70 transition-all duration-200 hover:border-[#E50914]/40 hover:bg-[#E50914]/5 hover:text-white has-[:checked]:border-[#E50914] has-[:checked]:bg-[#E50914]/10 has-[:checked]:text-white">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option['value'] }}" class="border-white/20 bg-transparent text-[#E50914] focus:ring-[#E50914]/30" @disabled($attemptLimitReached || $accessDenied)>
                                        <span>{{ $option['text'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($question->type === 'short_answer')
                            {{-- Short Answer Text Input --}}
                            <input
                                type="text"
                                name="answers[{{ $question->id }}]"
                                class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/30 focus:border-[#E50914] focus:outline-none focus:ring-1 focus:ring-[#E50914]"
                                placeholder="Enter your answer..."
                                @disabled($attemptLimitReached || $accessDenied)
                            >
                            <p class="mt-2 text-xs text-white/40">Provide a concise answer to the question above.</p>
                        @elseif ($question->type === 'essay')
                            {{-- Essay Textarea --}}
                            <textarea
                                name="answers[{{ $question->id }}]"
                                rows="6"
                                class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-white/30 focus:border-[#E50914] focus:outline-none focus:ring-1 focus:ring-[#E50914] resize-none"
                                placeholder="Write your answer here. Take your time to provide a thoughtful and complete response..."
                                @disabled($attemptLimitReached || $accessDenied)
                            ></textarea>
                            <p class="mt-2 text-xs text-white/40">Write a comprehensive answer. Your response will be reviewed by the instructor.</p>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="gradient-line"></div>

            {{-- Question Navigation --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">
                    <span id="question-counter">Question <span id="current-q">1</span> of <span id="total-q">{{ count($presentedQuestions) }}</span></span>
                </div>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <button class="lms-button-secondary" type="button" id="previous-question-btn" @disabled($attemptLimitReached || $accessDenied) aria-label="Go to previous question">
                        <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Previous
                    </button>
                    <button class="lms-button" type="button" id="next-question-btn" @disabled($attemptLimitReached || $accessDenied) aria-label="Go to next question">
                        Next
                        <svg class="ml-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <button class="lms-button hidden" id="review-answers-btn" type="button" @disabled($attemptLimitReached || $accessDenied) aria-label="Review all answers before submitting">
                        <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Review answers
                    </button>
                    <button class="lms-button hidden" id="final-submit-btn" type="submit" @disabled($attemptLimitReached || $accessDenied) aria-label="Submit quiz">
                        <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Submit quiz
                    </button>
                </div>
            </div>

            {{-- Answer Review Section --}}
            <div class="hidden rounded-3xl border border-white/10 bg-white/[0.02] p-6" id="review-section">
                <div class="mb-4 flex items-center gap-3">
                    <svg class="h-6 w-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <h3 class="text-lg font-semibold text-white">Review your answers</h3>
                </div>
                <p class="mb-4 text-sm text-white/60">Please review all your answers before final submission. You can go back to edit any answer.</p>
                <div class="space-y-3" id="review-list"></div>
                <div class="mt-6 flex flex-wrap gap-3 border-t border-white/10 pt-6">
                    <button class="lms-button-secondary" type="button" id="back-to-quiz-btn" aria-label="Go back and continue the quiz">
                        <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Back to quiz
                    </button>
                    <button class="lms-button" type="submit" aria-label="Confirm and submit quiz">
                        <svg class="mr-1.5 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Confirm submission
                    </button>
                </div>
            </div>

            <div class="gradient-line"></div>

            <div class="flex justify-end gap-3">
                @if ($quiz->lesson)
                    <a href="{{ route('lessons.show', $quiz->lesson) }}" class="lms-button-secondary">Cancel</a>
                @else
                    <a href="{{ route('quizzes.create') }}" class="lms-button-secondary">Cancel</a>
                @endif
            </div>
        </form>
    </div>

    <script>
        (function () {
            const timerBox = document.getElementById('quiz-timer-box');
            const form = document.getElementById('quiz-attempt-form');
            const display = document.getElementById('quiz-timer-display');
            const timerBar = document.getElementById('quiz-timer-bar');
            const attemptStartedAtInput = form ? form.querySelector('input[name="attempt_started_at"]') : null;
            const attemptActive = form && form.dataset.attemptActive === '1';
            const examGuardPanel = document.getElementById('exam-guard-panel');
            const enterExamModeBtn = document.getElementById('enter-exam-mode-btn');
            const tabWarningBox = document.getElementById('tab-warning-box');
            const tabWarningCount = document.getElementById('tab-warning-count');
            const questionCards = Array.from(document.querySelectorAll('.exam-question'));
            const nextQuestionBtn = document.getElementById('next-question-btn');
            const previousQuestionBtn = document.getElementById('previous-question-btn');
            const finalSubmitBtn = document.getElementById('final-submit-btn');
            const reviewAnswersBtn = document.getElementById('review-answers-btn');
            const backToQuizBtn = document.getElementById('back-to-quiz-btn');
            const reviewSection = document.getElementById('review-section');
            const reviewList = document.getElementById('review-list');
            const questionCounter = document.getElementById('question-counter');
            const currentQSpan = document.getElementById('current-q');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const questionDots = Array.from(document.querySelectorAll('.question-dot'));

            let examStarted = !attemptActive;
            let currentIndex = 0;
            let tabSwitchWarnings = 0;
            let submitted = false;
            let inReviewMode = false;

            const maxTabWarnings = 3;
            let totalQuestions = questionCards.length;
            let initialTimeLimitSeconds = 0;

            const updateProgress = () => {
                const answeredCount = questionCards.filter((card) => {
                    const questionType = card.dataset.questionType;

                    if (in_array(questionType, ['essay', 'short_answer'])) {
                        const textInput = card.querySelector('textarea, input[type="text"]');
                        return textInput && textInput.value.trim().length > 0;
                    } else {
                        return Array.from(card.querySelectorAll('input[type="radio"]')).some((input) => input.checked);
                    }
                }).length;

                const percentage = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
                if (progressBar) {
                    progressBar.style.width = percentage + '%';
                    progressBar.setAttribute('aria-valuenow', Math.round(percentage));
                }

                if (progressText) {
                    progressText.textContent = `${answeredCount} of ${totalQuestions}`;
                }

                // Update question dots
                questionDots.forEach((dot, index) => {
                    const card = questionCards[index];
                    const questionType = card.dataset.questionType;
                    let isAnswered = false;

                    if (in_array(questionType, ['essay', 'short_answer'])) {
                        const textInput = card.querySelector('textarea, input[type="text"]');
                        isAnswered = textInput && textInput.value.trim().length > 0;
                    } else {
                        isAnswered = Array.from(card.querySelectorAll('input[type="radio"]')).some((input) => input.checked);
                    }

                    const isCurrent = index === currentIndex;

                    dot.classList.toggle('bg-emerald-500', isAnswered);
                    dot.classList.toggle('border-emerald-500', isAnswered);
                    dot.classList.toggle('bg-white/10', !isAnswered && isCurrent);
                    dot.classList.toggle('border-white/30', !isAnswered && isCurrent);
                    dot.setAttribute('aria-current', isCurrent ? 'true' : 'false');
                });
            };

            const showQuestion = (index) => {
                if (inReviewMode) {
                    return;
                }

                questionCards.forEach((card, cardIndex) => {
                    card.classList.toggle('hidden', cardIndex !== index || !examStarted);
                });

                currentIndex = index;
                if (currentQSpan) {
                    currentQSpan.textContent = Math.min(index + 1, totalQuestions);
                }

                if (!nextQuestionBtn || !finalSubmitBtn || !previousQuestionBtn) {
                    return;
                }

                const onLastQuestion = index >= questionCards.length - 1;
                const onFirstQuestion = index === 0;

                previousQuestionBtn.classList.toggle('hidden', onFirstQuestion || !examStarted);
                nextQuestionBtn.classList.toggle('hidden', onLastQuestion || !examStarted);
                reviewAnswersBtn.classList.toggle('hidden', !onLastQuestion || !examStarted);
                finalSubmitBtn.classList.toggle('hidden', true);

                updateProgress();
            };

            const hasAnsweredCurrentQuestion = () => {
                const card = questionCards[currentIndex];

                if (!card) {
                    return true;
                }

                const questionType = card.dataset.questionType;

                if (in_array(questionType, ['essay', 'short_answer'])) {
                    // For text-based questions, check textarea/input value
                    const textInput = card.querySelector('textarea, input[type="text"]');
                    return textInput && textInput.value.trim().length > 0;
                } else {
                    // For MCQ/true-false, check radio selection
                    return Array.from(card.querySelectorAll('input[type="radio"]')).some((input) => input.checked);
                }
            };

            // Helper function to check if value is in array
            function in_array(needle, haystack) {
                return haystack.includes(needle);
            }

            const allQuestionsAnswered = () => {
                return questionCards.every((card) => {
                    const questionType = card.dataset.questionType;

                    if (in_array(questionType, ['essay', 'short_answer'])) {
                        const textInput = card.querySelector('textarea, input[type="text"]');
                        return textInput && textInput.value.trim().length > 0;
                    } else {
                        return Array.from(card.querySelectorAll('input[type="radio"]')).some((input) => input.checked);
                    }
                });
            };

            const firstUnansweredQuestionIndex = () => {
                return questionCards.findIndex((card) => {
                    const questionType = card.dataset.questionType;

                    if (in_array(questionType, ['essay', 'short_answer'])) {
                        const textInput = card.querySelector('textarea, input[type="text"]');
                        return !textInput || textInput.value.trim().length === 0;
                    } else {
                        return !Array.from(card.querySelectorAll('input[type="radio"]')).some((input) => input.checked);
                    }
                });
            };

            const showReviewScreen = () => {
                if (!allQuestionsAnswered()) {
                    alert('You must answer all questions before reviewing.');
                    return;
                }

                inReviewMode = true;
                questionCards.forEach((card) => card.classList.add('hidden'));

                reviewList.innerHTML = '';
                questionCards.forEach((card, index) => {
                    const questionText = card.querySelector('h4')?.textContent || `Question ${index + 1}`;
                    const questionType = card.dataset.questionType;
                    let selectedOption = 'Not answered';

                    if (in_array(questionType, ['essay', 'short_answer'])) {
                        const textInput = card.querySelector('textarea, input[type="text"]');
                        selectedOption = textInput?.value?.substring(0, 100) || 'Not answered';
                        if (selectedOption.length === 100) selectedOption += '...';
                    } else {
                        const selectedInput = card.querySelector('input[type="radio"]:checked');
                        selectedOption = selectedInput?.parentElement?.querySelector('span')?.textContent || 'Not answered';
                    }

                    const reviewItem = document.createElement('div');
                    reviewItem.className = 'rounded-2xl border border-white/10 bg-white/[0.02] p-4 transition hover:border-white/20';
                    reviewItem.innerHTML = `
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40">Question ${index + 1}</p>
                                <p class="mt-2 text-sm font-medium text-white">${questionText}</p>
                                <p class="mt-2 inline-flex items-center rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-1 text-xs text-emerald-300">
                                    ✓ ${selectedOption}
                                </p>
                            </div>
                            <button type="button" class="edit-answer-btn flex-shrink-0 rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-xs font-semibold text-white/70 transition hover:bg-white/10 hover:text-white" data-question-index="${index}">
                                Edit
                            </button>
                        </div>
                    `;
                    reviewList.appendChild(reviewItem);

                    reviewItem.querySelector('.edit-answer-btn').addEventListener('click', () => {
                        inReviewMode = false;
                        reviewSection.classList.add('hidden');
                        questionCards.forEach((c) => c.classList.add('hidden'));
                        showQuestion(index);
                    });
                });

                reviewSection.classList.remove('hidden');
                questionCards.forEach((card) => card.classList.add('hidden'));
            };

            const blockAction = (event, message) => {
                if (!attemptActive || !examStarted || inReviewMode) {
                    return;
                }

                event.preventDefault();
                alert(message);
            };

            const startExamMode = async () => {
                if (!attemptActive || examStarted) {
                    return;
                }

                try {
                    if (!document.fullscreenElement) {
                        await document.documentElement.requestFullscreen();
                    }
                } catch (error) {
                    alert('Fullscreen is required to start this exam. Please allow fullscreen and try again.');

                    return;
                }

                examStarted = true;

                if (attemptStartedAtInput) {
                    attemptStartedAtInput.value = new Date().toISOString();
                }

                if (examGuardPanel) {
                    examGuardPanel.classList.add('hidden');
                }

                showQuestion(currentIndex);
            };

            if (attemptActive) {
                questionCards.forEach((card) => card.classList.add('hidden'));
                showQuestion(currentIndex);
                updateProgress();

                // Listen to answer changes
                questionCards.forEach((card) => {
                    const questionType = card.dataset.questionType;

                    // Radio buttons for MCQ/True-False
                    card.querySelectorAll('input[type="radio"]').forEach((input) => {
                        input.addEventListener('change', updateProgress);
                    });

                    // Text inputs for short answer
                    const textInput = card.querySelector('input[type="text"]');
                    if (textInput) {
                        textInput.addEventListener('input', updateProgress);
                    }

                    // Textarea for essays
                    const textarea = card.querySelector('textarea');
                    if (textarea) {
                        textarea.addEventListener('input', updateProgress);
                    }
                });

                if (enterExamModeBtn) {
                    enterExamModeBtn.addEventListener('click', startExamMode);
                }

                if (nextQuestionBtn) {
                    nextQuestionBtn.addEventListener('click', () => {
                        if (!examStarted) {
                            alert('Start exam mode in fullscreen first.');
                            return;
                        }

                        if (!hasAnsweredCurrentQuestion()) {
                            alert('Answer the current question before moving forward.');
                            return;
                        }

                        if (currentIndex < questionCards.length - 1) {
                            currentIndex += 1;
                            showQuestion(currentIndex);
                        }
                    });
                }

                if (previousQuestionBtn) {
                    previousQuestionBtn.addEventListener('click', () => {
                        if (!examStarted) {
                            alert('Start exam mode in fullscreen first.');
                            return;
                        }

                        if (currentIndex > 0) {
                            currentIndex -= 1;
                            showQuestion(currentIndex);
                        }
                    });
                }

                if (reviewAnswersBtn) {
                    reviewAnswersBtn.addEventListener('click', showReviewScreen);
                }

                if (backToQuizBtn) {
                    backToQuizBtn.addEventListener('click', () => {
                        inReviewMode = false;
                        reviewSection.classList.add('hidden');
                        questionCards.forEach((card) => card.classList.add('hidden'));
                        showQuestion(currentIndex);
                    });
                }

                if (questionDots.length > 0) {
                    questionDots.forEach((dot) => {
                        dot.addEventListener('click', () => {
                            if (!examStarted || inReviewMode) return;
                            const index = parseInt(dot.dataset.questionIndex);
                            if (!Number.isNaN(index) && index >= 0 && index < questionCards.length) {
                                if (currentIndex !== index && !hasAnsweredCurrentQuestion()) {
                                    alert('Answer the current question before moving to another.');
                                    return;
                                }
                                showQuestion(index);
                            }
                        });
                    });
                }

                document.addEventListener('copy', (event) => blockAction(event, 'Copy is disabled during the exam.'));
                document.addEventListener('cut', (event) => blockAction(event, 'Cut is disabled during the exam.'));
                document.addEventListener('paste', (event) => blockAction(event, 'Paste is disabled during the exam.'));
                document.addEventListener('contextmenu', (event) => blockAction(event, 'Right click is disabled during the exam.'));
                document.addEventListener('selectstart', (event) => blockAction(event, 'Text selection is disabled during the exam.'));

                document.addEventListener('keydown', (event) => {
                    const forbiddenCombo = (event.ctrlKey || event.metaKey) && ['c', 'v', 'x', 'a', 'p', 's', 'u'].includes(event.key.toLowerCase());

                    if (forbiddenCombo) {
                        blockAction(event, 'This keyboard shortcut is disabled during the exam.');
                    }
                });

                document.addEventListener('visibilitychange', () => {
                    if (!examStarted || document.visibilityState !== 'hidden' || inReviewMode) {
                        return;
                    }

                    tabSwitchWarnings += 1;

                    if (tabWarningBox) {
                        tabWarningBox.classList.remove('hidden');
                    }

                    if (tabWarningCount) {
                        tabWarningCount.textContent = String(tabSwitchWarnings);
                    }

                    alert(`Tab switching detected. Warning ${tabSwitchWarnings}/${maxTabWarnings}.`);

                    if (tabSwitchWarnings >= maxTabWarnings) {
                        alert('Maximum tab switching warnings reached. This quiz will be auto-submitted.');
                        submitted = true;
                        form.requestSubmit();
                    }
                });

                document.addEventListener('fullscreenchange', () => {
                    if (!examStarted || inReviewMode) {
                        return;
                    }

                    if (!document.fullscreenElement) {
                        alert('You exited fullscreen mode. Re-enter fullscreen to continue the exam.');
                        examStarted = false;

                        if (examGuardPanel) {
                            examGuardPanel.classList.remove('hidden');
                        }

                        showQuestion(currentIndex);
                    }
                });
            }

            if (!timerBox || !form || !display) {
                return;
            }

            const timeLimitMinutes = Number(timerBox.dataset.timeLimitMinutes || 0);
            const autoSubmit = timerBox.dataset.autoSubmit === '1';

            if (!timeLimitMinutes || Number.isNaN(timeLimitMinutes)) {
                return;
            }

            let remainingSeconds = timeLimitMinutes * 60;
            initialTimeLimitSeconds = remainingSeconds;

            const format = (seconds) => {
                const safe = Math.max(0, seconds);
                const mins = Math.floor(safe / 60);
                const secs = safe % 60;

                return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            };

            const updateTimerColor = () => {
                if (!timerBar || !display) return;

                const percentageRemaining = (remainingSeconds / initialTimeLimitSeconds) * 100;
                timerBar.style.width = percentageRemaining + '%';

                // Color coding: green > 5 min, yellow 1-5 min, red < 1 min
                if (remainingSeconds > 300) {
                    timerBar.classList.remove('bg-yellow-500', 'bg-red-500');
                    timerBar.classList.add('bg-emerald-500');
                    display.classList.remove('text-yellow-300', 'text-red-300');
                    display.classList.add('text-white');
                } else if (remainingSeconds > 60) {
                    timerBar.classList.remove('bg-emerald-500', 'bg-red-500');
                    timerBar.classList.add('bg-yellow-500');
                    display.classList.remove('text-red-300');
                    display.classList.add('text-yellow-300');
                } else {
                    timerBar.classList.remove('bg-emerald-500', 'bg-yellow-500');
                    timerBar.classList.add('bg-red-500');
                    display.classList.remove('text-yellow-300');
                    display.classList.add('text-red-300');

                    // Pulse animation when critical
                    if (remainingSeconds < 60) {
                        timerBox.classList.add('animate-pulse');
                    }
                }
            };

            const tick = () => {
                display.textContent = format(remainingSeconds);
                updateTimerColor();

                if (remainingSeconds <= 0) {
                    if (autoSubmit && !submitted && examStarted) {
                        submitted = true;
                        inReviewMode = false;
                        reviewSection.classList.add('hidden');
                        form.requestSubmit();
                    }

                    return;
                }

                remainingSeconds -= 1;
                window.setTimeout(tick, 1000);
            };

            form.addEventListener('submit', (event) => {
                if (!attemptActive) {
                    return;
                }

                if (!examStarted) {
                    event.preventDefault();
                    alert('Start exam mode in fullscreen before submitting.');

                    return;
                }

                if (!inReviewMode && !allQuestionsAnswered()) {
                    event.preventDefault();
                    const unansweredIndex = firstUnansweredQuestionIndex();
                    if (unansweredIndex !== -1) {
                        inReviewMode = false;
                        reviewSection.classList.add('hidden');
                        currentIndex = unansweredIndex;
                        showQuestion(currentIndex);
                    }
                    alert('You must answer all questions before submitting.');
                }
            });

            tick();
        })();
    </script>
</x-app-layout>
