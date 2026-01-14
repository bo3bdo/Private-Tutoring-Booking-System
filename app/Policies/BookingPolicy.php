<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher() || $user->isStudent();
    }

    public function view(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $booking->student_id === $user->id;
        }

        if ($user->isTeacher()) {
            return $booking->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function update(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $booking->teacher_id === $user->teacherProfile?->id;
        }

        return false;
    }

    public function delete(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $booking->student_id === $user->id && $booking->isAwaitingPayment();
        }

        return false;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $booking->student_id === $user->id
                && ! $booking->isCancelled()
                && ! $booking->isCompleted();
        }

        if ($user->isTeacher()) {
            return $booking->teacher_id === $user->teacherProfile?->id
                && ! $booking->isCancelled()
                && ! $booking->isCompleted();
        }

        return false;
    }

    public function reschedule(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $booking->teacher_id === $user->teacherProfile?->id
                && ! $booking->isCancelled()
                && ! $booking->isCompleted();
        }

        return false;
    }

    public function markStatus(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $booking->teacher_id === $user->teacherProfile?->id
                && $booking->isConfirmed();
        }

        return false;
    }
}
