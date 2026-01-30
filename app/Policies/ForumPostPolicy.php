<?php

namespace App\Policies;

use App\Models\ForumPost;
use App\Models\User;

class ForumPostPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view posts
    }

    public function view(User $user, ForumPost $post): bool
    {
        return true; // All authenticated users can view posts
    }

    public function create(User $user): bool
    {
        return $user->isStudent() || $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, ForumPost $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    public function delete(User $user, ForumPost $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    public function react(User $user, ForumPost $post): bool
    {
        return $user->isStudent() || $user->isTeacher() || $user->isAdmin();
    }

    public function markAsAnswer(User $user, ForumPost $post): bool
    {
        // Only thread owner or admin can mark as answer
        return $user->id === $post->thread->user_id || $user->isAdmin();
    }
}
