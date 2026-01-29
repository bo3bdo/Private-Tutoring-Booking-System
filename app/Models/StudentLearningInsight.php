<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLearningInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_bookings',
        'completed_lessons',
        'courses_completed',
        'average_rating_given',
        'engagement_score',
        'subject_interests',
        'teacher_preferences',
        'last_analyzed_at',
    ];

    protected $casts = [
        'total_bookings' => 'integer',
        'completed_lessons' => 'integer',
        'courses_completed' => 'integer',
        'average_rating_given' => 'decimal:1',
        'engagement_score' => 'decimal:2',
        'subject_interests' => 'array',
        'teacher_preferences' => 'array',
        'last_analyzed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scopeHighEngagement($query, float $threshold = 70.0)
    {
        return $query->where('engagement_score', '>=', $threshold);
    }

    public function scopeNeedsReanalysis($query, int $hours = 24)
    {
        return $query->where(function ($q) use ($hours) {
            $q->whereNull('last_analyzed_at')
                ->orWhere('last_analyzed_at', '<', now()->subHours($hours));
        });
    }

    public function updateEngagementScore(): void
    {
        $score = $this->calculateEngagementScore();
        $this->update([
            'engagement_score' => $score,
            'last_analyzed_at' => now(),
        ]);
    }

    private function calculateEngagementScore(): float
    {
        $score = 0;

        // Booking activity (max 40 points)
        $score += min($this->total_bookings * 4, 40);

        // Course completion (max 30 points)
        $score += min($this->courses_completed * 10, 30);

        // Rating activity (max 20 points)
        if ($this->average_rating_given !== null) {
            $score += 20;
        }

        // Consistency bonus (max 10 points)
        if ($this->total_bookings >= 5) {
            $score += 10;
        }

        return min($score, 100);
    }
}
