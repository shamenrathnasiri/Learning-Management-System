<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'selected_option',
        'text_answer',
        'is_correct',
        'marks_obtained',
        'tutor_feedback',
        'graded_at',
        'graded_by',
        'status',
    ];

    protected $casts = [
        'selected_option' => 'integer',
        'is_correct' => 'boolean',
        'marks_obtained' => 'integer',
        'graded_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function gradedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Get the final marks for this answer (either is_correct for objective or marks_obtained for subjective).
     */
    public function getMarksAttribute(): int
    {
        // If marks_obtained is set (subjective grading), use that
        if ($this->marks_obtained !== null) {
            return $this->marks_obtained;
        }

        // For objective questions, 1 mark if correct, 0 if not
        return $this->is_correct ? 1 : 0;
    }

    /**
     * Check if this answer needs grading (subjective question not yet graded).
     */
    public function needsGrading(): bool
    {
        return $this->question->type === 'essay' && $this->status === 'pending';
    }

    /**
     * Check if this answer is graded or auto-gradable.
     */
    public function isGraded(): bool
    {
        return $this->status === 'graded' || in_array($this->question->type, ['multiple_choice', 'true_false', 'short_answer']);
    }
}
