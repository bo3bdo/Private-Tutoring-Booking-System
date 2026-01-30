<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reactable_id',
        'reactable_type',
        'reaction_type',
    ];

    protected function casts(): array
    {
        return [
            'reaction_type' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Toggle a reaction - if user has already reacted with this type, remove it, otherwise add it
     */
    public static function toggleReaction(User $user, Model $reactable, string $reactionType): self
    {
        $existingReaction = static::where('user_id', $user->id)
            ->where('reactable_id', $reactable->id)
            ->where('reactable_type', get_class($reactable))
            ->where('reaction_type', $reactionType)
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();

            return $existingReaction;
        }

        return static::create([
            'user_id' => $user->id,
            'reactable_id' => $reactable->id,
            'reactable_type' => get_class($reactable),
            'reaction_type' => $reactionType,
        ]);
    }

    /**
     * Check if user has reacted with specific type
     */
    public static function hasReaction(User $user, Model $reactable, string $reactionType): bool
    {
        return static::where('user_id', $user->id)
            ->where('reactable_id', $reactable->id)
            ->where('reactable_type', get_class($reactable))
            ->where('reaction_type', $reactionType)
            ->exists();
    }

    /**
     * Remove any existing vote from user on this item (upvote or downvote)
     */
    public static function removeVotes(User $user, Model $reactable): void
    {
        static::where('user_id', $user->id)
            ->where('reactable_id', $reactable->id)
            ->where('reactable_type', get_class($reactable))
            ->whereIn('reaction_type', ['upvote', 'downvote'])
            ->delete();
    }

    protected static function booted(): void
    {
        static::created(function ($reaction) {
            // Award small points for receiving positive reactions
            if (in_array($reaction->reaction_type, ['upvote', 'like'])) {
                try {
                    $reactable = $reaction->reactable;
                    // Don't award points for self-reactions and ensure user exists
                    if ($reactable && isset($reactable->user_id) && $reactable->user_id !== $reaction->user_id) {
                        $user = $reactable->user;
                        if ($user) {
                            $user->pointsHistory()->create([
                                'points' => 1,
                                'type' => 'earned',
                                'source' => 'forum_reaction_received',
                                'description' => 'Received '.$reaction->reaction_type.' on forum content',
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the reaction
                    \Log::error('Failed to award points for forum reaction: '.$e->getMessage());
                }
            }
        });
    }
}
