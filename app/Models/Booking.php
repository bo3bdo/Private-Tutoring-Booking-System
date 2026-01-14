<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use App\Enums\MeetingProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'time_slot_id',
        'start_at',
        'end_at',
        'status',
        'lesson_mode',
        'location_id',
        'meeting_provider',
        'meeting_url',
        'meeting_meta',
        'notes',
        'cancelled_at',
        'cancellation_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => BookingStatus::class,
            'lesson_mode' => LessonMode::class,
            'meeting_provider' => MeetingProvider::class,
            'meeting_meta' => 'array',
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function isAwaitingPayment(): bool
    {
        return $this->status === BookingStatus::AwaitingPayment;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed;
    }

    public function isCancelled(): bool
    {
        return $this->status === BookingStatus::Cancelled;
    }

    public function isCompleted(): bool
    {
        return $this->status === BookingStatus::Completed;
    }
}
