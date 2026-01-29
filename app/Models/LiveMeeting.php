<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'provider',
        'meeting_id',
        'meeting_url',
        'join_url',
        'host_url',
        'password',
        'scheduled_at',
        'duration_minutes',
        'metadata',
        'started_at',
        'ended_at',
        'recording_url',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function isActive(): bool
    {
        return $this->started_at !== null && $this->ended_at === null;
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at > now();
    }

    public function isCompleted(): bool
    {
        return $this->ended_at !== null;
    }
}
