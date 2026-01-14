<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'price',
        'currency',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function lessonsCount(): int
    {
        return $this->lessons()->count();
    }

    public function completedLessonsCountFor(User $student): int
    {
        return $this->lessons()
            ->whereHas('progress', function ($query) use ($student) {
                $query->where('student_id', $student->id)
                    ->whereNotNull('completed_at');
            })
            ->count();
    }

    public function progressPercentFor(User $student): float
    {
        $totalLessons = $this->lessonsCount();

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->completedLessonsCountFor($student);

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    public function isEnrolledBy(User $student): bool
    {
        return $this->enrollments()
            ->where('student_id', $student->id)
            ->exists();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewable_id')
            ->where('reviewable_type', self::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'resourceable_id')
            ->where('resourceable_type', self::class);
    }

    public function averageRating(): float
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
    }

    public function reviewsCount(): int
    {
        return $this->reviews()
            ->where('is_approved', true)
            ->count();
    }
}
