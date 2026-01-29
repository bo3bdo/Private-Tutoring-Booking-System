<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'points',
        'bookings_count',
        'courses_completed',
        'rank',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'points' => 'integer',
        'bookings_count' => 'integer',
        'courses_completed' => 'integer',
        'rank' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForPeriod($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->where('year', now()->year)->where('month', now()->month);
    }

    public function scopeTop($query, int $limit = 10)
    {
        return $query->orderByDesc('points')->limit($limit);
    }
}
