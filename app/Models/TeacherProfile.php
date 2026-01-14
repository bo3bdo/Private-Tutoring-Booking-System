<?php

namespace App\Models;

use App\Enums\MeetingProvider;
use App\Models\Booking;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherProfile extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'hourly_rate',
        'is_active',
        'supports_online',
        'supports_in_person',
        'default_location_id',
        'default_meeting_provider',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'supports_online' => 'boolean',
            'supports_in_person' => 'boolean',
            'hourly_rate' => 'decimal:2',
            'default_meeting_provider' => MeetingProvider::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id')
            ->withTimestamps();
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(TeacherAvailability::class, 'teacher_id');
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class, 'teacher_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'teacher_id');
    }

    public function defaultLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'default_location_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewable_id')
            ->where('reviewable_type', self::class);
    }

    public function averageRating(): float
    {
        // Get booking IDs for this teacher
        $bookingIds = $this->bookings()->pluck('id');

        // Get course IDs for this teacher
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        // Get all approved reviews: direct teacher reviews + booking reviews + course reviews
        $avgRating = Review::where('is_approved', true)
            ->where(function ($query) use ($bookingIds, $courseIds) {
                // Direct teacher profile reviews
                $query->where(function ($q) {
                    $q->where('reviewable_type', self::class)
                        ->where('reviewable_id', $this->id);
                })
                // Booking reviews
                ->orWhere(function ($q) use ($bookingIds) {
                    if ($bookingIds->isNotEmpty()) {
                        $q->where('reviewable_type', Booking::class)
                            ->whereIn('reviewable_id', $bookingIds);
                    }
                })
                // Course reviews
                ->orWhere(function ($q) use ($courseIds) {
                    if ($courseIds->isNotEmpty()) {
                        $q->where('reviewable_type', Course::class)
                            ->whereIn('reviewable_id', $courseIds);
                    }
                });
            })
            ->avg('rating');

        return round((float) ($avgRating ?? 0), 2);
    }

    public function reviewsCount(): int
    {
        // Get booking IDs for this teacher
        $bookingIds = $this->bookings()->pluck('id');

        // Get course IDs for this teacher
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        // Count all approved reviews: direct teacher reviews + booking reviews + course reviews
        return Review::where('is_approved', true)
            ->where(function ($query) use ($bookingIds, $courseIds) {
                // Direct teacher profile reviews
                $query->where(function ($q) {
                    $q->where('reviewable_type', self::class)
                        ->where('reviewable_id', $this->id);
                })
                // Booking reviews
                ->orWhere(function ($q) use ($bookingIds) {
                    if ($bookingIds->isNotEmpty()) {
                        $q->where('reviewable_type', Booking::class)
                            ->whereIn('reviewable_id', $bookingIds);
                    }
                })
                // Course reviews
                ->orWhere(function ($q) use ($courseIds) {
                    if ($courseIds->isNotEmpty()) {
                        $q->where('reviewable_type', Course::class)
                            ->whereIn('reviewable_id', $courseIds);
                    }
                });
            })
            ->count();
    }

    public function getAllReviews()
    {
        // Get booking IDs for this teacher
        $bookingIds = $this->bookings()->pluck('id');

        // Get course IDs for this teacher
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        // Get all approved reviews: direct teacher reviews + booking reviews + course reviews
        return Review::where('is_approved', true)
            ->where(function ($query) use ($bookingIds, $courseIds) {
                // Direct teacher profile reviews
                $query->where(function ($q) {
                    $q->where('reviewable_type', self::class)
                        ->where('reviewable_id', $this->id);
                })
                // Booking reviews
                ->orWhere(function ($q) use ($bookingIds) {
                    if ($bookingIds->isNotEmpty()) {
                        $q->where('reviewable_type', Booking::class)
                            ->whereIn('reviewable_id', $bookingIds);
                    }
                })
                // Course reviews
                ->orWhere(function ($q) use ($courseIds) {
                    if ($courseIds->isNotEmpty()) {
                        $q->where('reviewable_type', Course::class)
                            ->whereIn('reviewable_id', $courseIds);
                    }
                });
            })
            ->with('user')
            ->latest()
            ->get();
    }
}
