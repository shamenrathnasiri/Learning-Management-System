<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isTutor() || $this->user()?->isAdministrator();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'instructions' => ['nullable', 'string', 'max:5000'],
            'course_id' => ['nullable', 'integer', 'between:1,4'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:10000'],
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.type' => ['required', 'in:mcq,true_false,short_answer,essay'],
            'questions.*.question' => ['required', 'string', 'max:1000'],
            'questions.*.marks' => ['required', 'integer', 'min:0'],
            'questions.*.explanation' => ['nullable', 'string', 'max:2000'],
            // MCQ
            'questions.*.option_one' => ['required_if:questions.*.type,mcq', 'string', 'max:255'],
            'questions.*.option_two' => ['required_if:questions.*.type,mcq', 'string', 'max:255'],
            'questions.*.option_three' => ['nullable', 'string', 'max:255'],
            'questions.*.option_four' => ['nullable', 'string', 'max:255'],
            'questions.*.correct_option' => ['required_if:questions.*.type,mcq', 'integer', 'between:0,3'],
            // True/False & Short Answer & Essay
            'questions.*.correct_answer' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->filled('course_id') && ! $this->filled('lesson_id')) {
                $validator->errors()->add('lesson_id', 'Select a course or a lesson for this quiz.');
            }
        });
    }
}
