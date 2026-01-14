<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Resource extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resourceable_type',
        'resourceable_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resourceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function isPublic(): bool
    {
        return $this->is_public;
    }
}
