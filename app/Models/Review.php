<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'teaching_style_rating',
        'communication_rating',
        'punctuality_rating',
        'comment',
        'images',
        'teacher_response',
        'teacher_response_at',
        'is_approved',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'teaching_style_rating' => 'integer',
            'communication_rating' => 'integer',
            'punctuality_rating' => 'integer',
            'images' => 'array',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
            'teacher_response_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function approve(): void
    {
        $this->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function hasTeacherResponse(): bool
    {
        return ! empty($this->teacher_response);
    }

    public function addTeacherResponse(string $response): void
    {
        $this->update([
            'teacher_response' => $response,
            'teacher_response_at' => now(),
        ]);
    }
}
