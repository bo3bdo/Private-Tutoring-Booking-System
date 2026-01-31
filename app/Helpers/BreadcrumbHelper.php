<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    public static function bookings(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Bookings Management'), 'url' => null],
        ];
    }

    public static function bookingShow($booking): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Bookings'), 'url' => route('admin.bookings.index')],
            ['label' => $booking->subject->name, 'url' => null],
        ];
    }

    public static function courses(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Courses'), 'url' => null],
        ];
    }

    public static function courseShow($course): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Courses'), 'url' => route('admin.courses.index')],
            ['label' => $course->title, 'url' => null],
        ];
    }

    public static function teachers(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Teachers'), 'url' => null],
        ];
    }

    public static function teacherShow($teacher): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Teachers'), 'url' => route('admin.teachers.index')],
            ['label' => $teacher->user->name, 'url' => null],
        ];
    }

    public static function students(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Students'), 'url' => null],
        ];
    }

    public static function studentShow($student): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Students'), 'url' => route('admin.students.index')],
            ['label' => $student->name, 'url' => null],
        ];
    }

    public static function payments(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Payments'), 'url' => null],
        ];
    }

    public static function reviews(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Reviews'), 'url' => null],
        ];
    }

    public static function supportTickets(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Support Tickets'), 'url' => null],
        ];
    }

    public static function subjects(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Subjects'), 'url' => null],
        ];
    }

    public static function locations(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Locations'), 'url' => null],
        ];
    }

    public static function discounts(): array
    {
        return [
            ['label' => __('common.Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('common.Discounts'), 'url' => null],
        ];
    }
}
