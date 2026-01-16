<?php

namespace App\Policies;

use App\Models\TeacherAvailability;
use App\Models\User;

class TeacherAvailabilityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, TeacherAvailability $availability): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $availability->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, TeacherAvailability $availability): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $availability->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function delete(User $user, TeacherAvailability $availability): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $availability->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }
}
