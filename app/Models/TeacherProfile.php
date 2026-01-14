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
}
