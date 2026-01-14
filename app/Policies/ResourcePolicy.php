<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Resource $resource): bool
    {
        if ($resource->is_public) {
            return true;
        }

        return $resource->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Resource $resource): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $resource->user_id === $user->id;
    }

    public function delete(User $user, Resource $resource): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $resource->user_id === $user->id;
    }
}
