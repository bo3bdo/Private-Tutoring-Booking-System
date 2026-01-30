<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'user_id',
        'content',
        'is_answer',
    ];

    protected function casts(): array
    {
        return [
            'is_answer' => 'boolean',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(ForumReaction::class, 'reactable');
    }

    public function getUpvotesCountAttribute(): int
    {
        return $this->reactions()->where('reaction_type', 'upvote')->count();
    }

    public function getDownvotesCountAttribute(): int
    {
        return $this->reactions()->where('reaction_type', 'downvote')->count();
    }

    public function getLikesCountAttribute(): int
    {
        return $this->reactions()->where('reaction_type', 'like')->count();
    }

    public function getScoreAttribute(): int
    {
        return $this->upvotes_count - $this->downvotes_count;
    }

    public function scopeWithReactions($query)
    {
        return $query->withCount(['reactions as upvotes_count' => function ($query) {
            $query->where('reaction_type', 'upvote');
        }, 'reactions as downvotes_count' => function ($query) {
            $query->where('reaction_type', 'downvote');
        }, 'reactions as likes_count' => function ($query) {
            $query->where('reaction_type', 'like');
        }]);
    }

    protected static function booted(): void
    {
        static::created(function ($post) {
            // Award points for creating a post
            $post->user->pointsHistory()->create([
                'points' => 5,
                'type' => 'earned',
                'source' => 'forum_post_created',
                'description' => 'Created forum post in thread: '.$post->thread->title,
            ]);

            // Update thread's last post and reply count
            $post->thread->updateLastPost();
        });
    }
}
