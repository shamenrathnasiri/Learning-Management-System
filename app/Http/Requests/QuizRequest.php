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
            'passing_score' => ['required', 'integer', 'min:0', 'max:100'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string', 'max:1000'],
            'questions.*.option_one' => ['required', 'string', 'max:255'],
            'questions.*.option_two' => ['required', 'string', 'max:255'],
            'questions.*.option_three' => ['required', 'string', 'max:255'],
            'questions.*.option_four' => ['required', 'string', 'max:255'],
            'questions.*.correct_option' => ['required', 'integer', 'between:0,3'],
        ];
    }
}
