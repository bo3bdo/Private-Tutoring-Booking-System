<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'recommendation_data',
        'context',
        'algorithm_version',
        'generated_at',
    ];

    protected $casts = [
        'recommendation_data' => 'array',
        'context' => 'array',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('generated_at', '>=', now()->subHours($hours));
    }

    public function isFresh(): bool
    {
        return $this->generated_at->diffInHours(now()) < 24;
    }
}
