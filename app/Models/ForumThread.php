<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'content',
        'is_pinned',
        'is_locked',
        'views_count',
        'reply_count',
        'best_answer_post_id',
        'last_post_at',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'views_count' => 'integer',
            'reply_count' => 'integer',
            'last_post_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'thread_id')->oldest();
    }

    public function bestAnswer(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'best_answer_post_id');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(ForumReaction::class, 'reactable');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ForumSubscription::class, 'thread_id');
    }

    public function isSubscribedBy(User $user): bool
    {
        return $this->subscriptions()->where('user_id', $user->id)->exists();
    }

    public function subscribe(User $user): ForumSubscription
    {
        return $this->subscriptions()->firstOrCreate(['user_id' => $user->id]);
    }

    public function unsubscribe(User $user): bool
    {
        return $this->subscriptions()->where('user_id', $user->id)->delete() > 0;
    }

    public function updateLastPost(): void
    {
        $lastPost = $this->posts()->latest()->first();
        $this->update([
            'last_post_at' => $lastPost?->created_at,
            'reply_count' => $this->posts()->count() - 1, // Exclude original post
        ]);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function markAsBestAnswer(ForumPost $post): void
    {
        $this->update(['best_answer_post_id' => $post->id]);
        $post->update(['is_answer' => true]);

        // Award points to the user who provided the best answer
        $post->user->pointsHistory()->create([
            'points' => 25,
            'type' => 'earned',
            'source' => 'best_answer_selected',
            'description' => 'Best answer selected in forum thread: '.$this->title,
        ]);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    public function scopeNotLocked($query)
    {
        return $query->where('is_locked', false);
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

    protected static function booted(): void
    {
        static::created(function ($thread) {
            // Award points for creating a thread
            $thread->user->pointsHistory()->create([
                'points' => 10,
                'type' => 'earned',
                'source' => 'forum_thread_created',
                'description' => 'Created forum thread: '.$thread->title,
            ]);

            // Auto-subscribe thread creator
            $thread->subscribe($thread->user);
        });
    }
}
