<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'slug',
        'module',
        'description',
        'thumbnail_path',
        'content_type',
        'content',
        'video_url',
        'video_path',
        'duration',
        'release_date',
        'status',
        'attachment_path',
        'attachment_paths',
    ];

    protected $casts = [
        'release_date' => 'date',
        'attachment_paths' => 'array',
        'duration' => 'integer',
    ];

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
