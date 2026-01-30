<?php

namespace App\Policies;

use App\Models\ForumThread;
use App\Models\User;

class ForumThreadPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view threads
    }

    public function view(User $user, ForumThread $thread): bool
    {
        return true; // All authenticated users can view threads
    }

    public function create(User $user): bool
    {
        return $user->isStudent() || $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, ForumThread $thread): bool
    {
        return $user->id === $thread->user_id || $user->isAdmin();
    }

    public function delete(User $user, ForumThread $thread): bool
    {
        return $user->id === $thread->user_id || $user->isAdmin();
    }

    public function pin(User $user): bool
    {
        return $user->isAdmin();
    }

    public function lock(User $user): bool
    {
        return $user->isAdmin();
    }

    public function markBestAnswer(User $user, ForumThread $thread): bool
    {
        return $user->id === $thread->user_id || $user->isAdmin();
    }

    public function subscribe(User $user, ForumThread $thread): bool
    {
        return $user->isStudent() || $user->isTeacher() || $user->isAdmin();
    }

    public function react(User $user, ForumThread $thread): bool
    {
        return $user->isStudent() || $user->isTeacher() || $user->isAdmin();
    }
}
