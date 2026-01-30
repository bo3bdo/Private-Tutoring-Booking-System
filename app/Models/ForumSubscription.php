<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'thread_id',
        'last_read_at',
    ];

    protected function casts(): array
    {
        return [
            'last_read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }

    public function getUnreadCountAttribute(): int
    {
        if (! $this->last_read_at) {
            return $this->thread->posts()->count();
        }

        return $this->thread->posts()
            ->where('created_at', '>', $this->last_read_at)
            ->count();
    }

    public function scopeWithUnreadCount($query)
    {
        return $query->withCount(['thread.posts as unread_posts' => function ($query) {
            $query->where('created_at', '>', function ($subQuery) {
                $subQuery->select('last_read_at')
                    ->from('forum_subscriptions')
                    ->whereColumn('forum_subscriptions.thread_id', 'forum_posts.thread_id')
                    ->whereColumn('forum_subscriptions.user_id', 'forum_subscriptions.user_id')
                    ->limit(1);
            })->orWhereNull('last_read_at');
        }]);
    }
}
