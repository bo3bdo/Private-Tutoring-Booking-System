<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    /** @use HasFactory<\Database\Factories\LessonProgressFactory> */
    use HasFactory;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'course_id',
        'lesson_id',
        'student_id',
        'watched_seconds',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'watched_seconds' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function completionPercent(): float
    {
        if (! $this->lesson->duration_seconds || $this->lesson->duration_seconds === 0) {
            return 0;
        }

        return min(100, round(($this->watched_seconds / $this->lesson->duration_seconds) * 100, 2));
    }
}
