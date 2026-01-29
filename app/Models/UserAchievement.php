<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'progress',
        'unlocked_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'unlocked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function isUnlocked(): bool
    {
        return $this->unlocked_at !== null;
    }

    public function unlock(): void
    {
        $this->update([
            'progress' => $this->achievement->threshold,
            'unlocked_at' => now(),
        ]);
    }
}
