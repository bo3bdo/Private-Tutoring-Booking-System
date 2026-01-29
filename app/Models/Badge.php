<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'tier',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTier($query, string $tier)
    {
        return $query->where('tier', $tier);
    }

    public function getTierOrder(): int
    {
        return match ($this->tier) {
            'bronze' => 1,
            'silver' => 2,
            'gold' => 3,
            'platinum' => 4,
            default => 0,
        };
    }
}
