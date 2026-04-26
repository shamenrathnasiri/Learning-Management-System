<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttemptQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStudent() || $this->user()?->isAdministrator() || $this->user()?->isTutor();
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', 'between:0,3'],
        ];
    }
}
