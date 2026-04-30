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
        $provider = $this->input('live_class_provider');

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
            'live_class_provider' => ['nullable', 'in:zoom,google_meet'],
            'live_class_title' => ['nullable', 'string', 'max:255'],
            'live_class_start_at' => ['nullable', 'date'],
            'live_class_duration' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'live_class_meeting_url' => [
                'nullable',
                'url',
                'max:2048',
                'required_with:live_class_provider',
                function (string $attribute, mixed $value, \Closure $fail) use ($provider): void {
                    if (! is_string($value) || $value === '') {
                        return;
                    }

                    $host = strtolower((string) parse_url($value, PHP_URL_HOST));

                    if ($provider === 'zoom' && ! str_contains($host, 'zoom.us') && ! str_contains($host, 'zoom.com')) {
                        $fail('The Zoom meeting URL must point to a Zoom domain.');
                    }

                    if ($provider === 'google_meet' && ! str_contains($host, 'meet.google.com')) {
                        $fail('The Google Meet URL must point to meet.google.com.');
                    }
                },
            ],
            'live_class_meeting_code' => ['nullable', 'string', 'max:255'],
            'live_class_passcode' => ['nullable', 'string', 'max:255'],
        ];
    }
}
