<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Quiz;
use App\Models\QuizAttemptAnswer;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'sort_order',
        'question',
        'difficulty',
        'tags',
        'option_one',
        'option_two',
        'option_three',
        'option_four',
        'correct_option',
        'type',
        'marks',
        'explanation',
        'correct_answer',
        'media_path',
        'media_type',
        'media_name',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'correct_option' => 'integer',
        'marks' => 'integer',
        'tags' => 'array',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }
}
