<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(ForumPost::class, ForumThread::class, 'category_id', 'thread_id');
    }

    public function activeThreads(): HasMany
    {
        return $this->threads()->latest('last_post_at');
    }

    public function getThreadCountAttribute(): int
    {
        return $this->threads()->count();
    }

    public function getPostCountAttribute(): int
    {
        return $this->threads()->withCount('posts')->get()->sum('posts_count');
    }

    public function getLatestPostAttribute(): ?ForumPost
    {
        return $this->threads()
            ->with('posts')
            ->get()
            ->map(function ($thread) {
                return $thread->posts->last();
            })
            ->filter()
            ->sortByDesc('created_at')
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
