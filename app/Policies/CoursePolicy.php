<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Anyone can browse published courses
    }

    public function view(User $user, Course $course): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Teacher can view own courses
        if ($user->isTeacher() && $course->teacher_id === $user->id) {
            return true;
        }

        // Students can view published courses
        if ($user->isStudent()) {
            return $course->is_published;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $course->teacher_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $course->teacher_id === $user->id;
        }

        return false;
    }

    public function publish(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function manageLessons(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function viewSales(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $course->teacher_id === $user->id;
        }

        return false;
    }

    public function accessLearning(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStudent()) {
            return $course->isEnrolledBy($user);
        }

        return false;
    }
}
