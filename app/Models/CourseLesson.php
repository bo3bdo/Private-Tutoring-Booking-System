<?php

namespace App\Models;

use App\Enums\VideoProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLesson extends Model
{
    /** @use HasFactory<\Database\Factories\CourseLessonFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'summary',
        'sort_order',
        'video_provider',
        'video_url',
        'duration_seconds',
        'is_free_preview',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'video_provider' => VideoProvider::class,
            'duration_seconds' => 'integer',
            'is_free_preview' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    public function isCompletedBy(User $student): bool
    {
        return $this->progress()
            ->where('student_id', $student->id)
            ->whereNotNull('completed_at')
            ->exists();
    }

    public function getProgressFor(User $student): ?LessonProgress
    {
        return $this->progress()
            ->where('student_id', $student->id)
            ->first();
    }

    public function canAccess(User $student): bool
    {
        if ($this->is_free_preview) {
            return true;
        }

        return $this->course->isEnrolledBy($student);
    }

    public function durationFormatted(): string
    {
        if (! $this->duration_seconds) {
            return 'N/A';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
