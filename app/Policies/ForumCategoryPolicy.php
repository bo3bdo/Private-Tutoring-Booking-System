<?php

namespace App\Policies;

use App\Models\ForumCategory;
use App\Models\User;

class ForumCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view categories
    }

    public function view(User $user, ForumCategory $category): bool
    {
        return $category->is_active || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin(); // Only admins can create categories
    }

    public function update(User $user, ForumCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ForumCategory $category): bool
    {
        return $user->isAdmin();
    }

    public function reorder(User $user): bool
    {
        return $user->isAdmin();
    }
}
