<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lesson_id',
        'user_id',
        'title',
        'description',
        'instructions',
        'time_limit_minutes',
        'total_marks',
        'passing_score',
        'shuffle_questions',
        'shuffle_answers',
        'max_attempts',
        'result_visibility',
        'show_correct_answers',
        'show_explanations',
        'starts_at',
        'ends_at',
        'restrict_to_enrolled_students',
        'auto_submit_on_expiry',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'time_limit_minutes' => 'integer',
        'total_marks' => 'integer',
        'passing_score' => 'integer',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'max_attempts' => 'integer',
        'show_correct_answers' => 'boolean',
        'show_explanations' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'restrict_to_enrolled_students' => 'boolean',
        'auto_submit_on_expiry' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
