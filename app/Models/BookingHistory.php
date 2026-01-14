<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingHistory extends Model
{
    /** @use HasFactory<\Database\Factories\BookingHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'actor_id',
        'action',
        'old_status',
        'new_status',
        'old_payload',
        'new_payload',
    ];

    protected function casts(): array
    {
        return [
            'old_payload' => 'array',
            'new_payload' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
