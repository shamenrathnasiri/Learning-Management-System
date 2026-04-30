<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isTutor() === true || $this->user()?->isAdministrator() === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'integer', 'between:1,4'],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/webm', 'max:51200', 'required_without:video_url'],
            'video_url' => ['nullable', 'url', 'max:2048', 'required_without:video_file'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:10240'],
            'duration' => ['required', 'integer', 'min:1', 'max:10000'],
            'release_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
