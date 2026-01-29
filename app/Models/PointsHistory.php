<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointsHistory extends Model
{
    use HasFactory;

    protected $table = 'points_history';

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'source',
        'description',
        'pointable_type',
        'pointable_id',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    public function scopeSpent($query)
    {
        return $query->where('type', 'spent');
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }
}
