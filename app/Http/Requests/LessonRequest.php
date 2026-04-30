<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isTutor() || $this->user()?->isAdministrator();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'content_type' => ['required', 'in:text,video,file'],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
