<?php

namespace App\Models;

use App\Enums\SlotStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimeSlot extends Model
{
    /** @use HasFactory<\Database\Factories\TimeSlotFactory> */
    use HasFactory;

    protected $table = 'teacher_time_slots';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'start_at',
        'end_at',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => SlotStatus::class,
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class, 'time_slot_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === SlotStatus::Available;
    }

    public function isBlocked(): bool
    {
        return $this->status === SlotStatus::Blocked;
    }

    public function isBooked(): bool
    {
        return $this->status === SlotStatus::Booked;
    }
}
