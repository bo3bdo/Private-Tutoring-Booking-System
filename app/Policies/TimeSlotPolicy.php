<?php

namespace App\Policies;

use App\Models\TimeSlot;
use App\Models\User;

class TimeSlotPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher() || $user->isStudent();
    }

    public function view(User $user, TimeSlot $timeSlot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            // Students can view slots to see details, even if booked
            // The 'book' method will check if it's available for booking
            return true;
        }

        if ($user->isTeacher()) {
            return $timeSlot->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function update(User $user, TimeSlot $timeSlot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $timeSlot->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function delete(User $user, TimeSlot $timeSlot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $timeSlot->teacher_id === $user->teacherProfile?->id
                && $timeSlot->isAvailable();
        }

        return false;
    }

    public function block(User $user, TimeSlot $timeSlot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $timeSlot->teacher_id === $user->teacherProfile?->id
                && $timeSlot->isAvailable();
        }

        return false;
    }

    public function unblock(User $user, TimeSlot $timeSlot): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $timeSlot->teacher_id === $user->teacherProfile?->id
                && $timeSlot->isBlocked();
        }

        return false;
    }

    public function book(User $user, TimeSlot $timeSlot): bool
    {
        if (! $user->isStudent()) {
            return false;
        }

        // Check if slot is available
        if (! $timeSlot->isAvailable()) {
            return false;
        }

        // Check if slot is not in the past
        if ($timeSlot->start_at < now()) {
            return false;
        }

        return true;
    }
}
