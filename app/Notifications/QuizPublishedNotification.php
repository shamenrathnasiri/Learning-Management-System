<?php

namespace App\Notifications;

use App\Models\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QuizPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Quiz $quiz)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quiz_published',
            'quiz_id' => $this->quiz->id,
            'title' => $this->quiz->title,
            'lesson_title' => $this->quiz->lesson?->title,
            'message' => sprintf('A new quiz "%s" is now live.', $this->quiz->title),
            'url' => route('quizzes.show', $this->quiz),
            'published_at' => $this->quiz->published_at?->toIso8601String() ?? now()->toIso8601String(),
        ];
    }
}
