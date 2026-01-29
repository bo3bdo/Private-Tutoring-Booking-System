<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLearningPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_subjects',
        'preferred_times',
        'preferred_lesson_mode',
        'learning_goals',
        'budget_per_hour',
        'learning_style',
    ];

    protected $casts = [
        'preferred_subjects' => 'array',
        'preferred_times' => 'array',
        'learning_goals' => 'array',
        'learning_style' => 'array',
        'budget_per_hour' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByLearningStyle($query, string $style)
    {
        return $query->whereJsonContains('learning_style', $style);
    }
}
