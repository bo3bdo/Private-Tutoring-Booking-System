<?php

namespace App\Models;

use App\Enums\MeetingProvider;
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

    /**
     * @return array{average: float, count: int, reviews: \Illuminate\Database\Eloquent\Collection<int, Review>}
     */
    public function getReviewsSummary(): array
    {
        $bookingIds = $this->bookings()->pluck('id');
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        $reviews = Review::where('is_approved', true)
            ->where($this->reviewsScopeCallback($bookingIds, $courseIds))
            ->with('user')
            ->latest('created_at')
            ->get();

        $average = $reviews->isEmpty() ? 0.0 : round((float) $reviews->avg('rating'), 2);
        $count = $reviews->count();

        return [
            'average' => $average,
            'count' => $count,
            'reviews' => $reviews,
        ];
    }

    public function averageRating(): float
    {
        $bookingIds = $this->bookings()->pluck('id');
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        $avgRating = Review::where('is_approved', true)
            ->where($this->reviewsScopeCallback($bookingIds, $courseIds))
            ->avg('rating');

        return round((float) ($avgRating ?? 0), 2);
    }

    public function reviewsCount(): int
    {
        $bookingIds = $this->bookings()->pluck('id');
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        return Review::where('is_approved', true)
            ->where($this->reviewsScopeCallback($bookingIds, $courseIds))
            ->count();
    }

    public function getAllReviews()
    {
        $bookingIds = $this->bookings()->pluck('id');
        $courseIds = Course::where('teacher_id', $this->user_id)->pluck('id');

        return Review::where('is_approved', true)
            ->where($this->reviewsScopeCallback($bookingIds, $courseIds))
            ->with('user')
            ->latest('created_at')
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>  $bookingIds
     * @param  \Illuminate\Support\Collection<int, int>  $courseIds
     */
    protected function reviewsScopeCallback($bookingIds, $courseIds): \Closure
    {
        return function ($query) use ($bookingIds, $courseIds) {
            $query->where(function ($q) {
                $q->where('reviewable_type', self::class)
                    ->where('reviewable_id', $this->id);
            })
                ->orWhere(function ($q) use ($bookingIds) {
                    if ($bookingIds->isNotEmpty()) {
                        $q->where('reviewable_type', Booking::class)
                            ->whereIn('reviewable_id', $bookingIds);
                    }
                })
                ->orWhere(function ($q) use ($courseIds) {
                    if ($courseIds->isNotEmpty()) {
                        $q->where('reviewable_type', Course::class)
                            ->whereIn('reviewable_id', $courseIds);
                    }
                });
        };
    }
}
