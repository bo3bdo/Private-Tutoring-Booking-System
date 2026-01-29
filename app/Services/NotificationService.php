<?php

namespace App\Services;

use App\Enums\NotificationChannel;
use App\Models\Booking;
use App\Models\NotificationLog;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingNoShowNotification;
use App\Notifications\BookingReminderNotification;
use App\Notifications\BookingRescheduledNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function sendBookingCreated(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingCreatedNotification($booking));
    }

    public function sendBookingAwaitingPayment(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingCreatedNotification($booking));
    }

    public function sendBookingConfirmed(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingConfirmedNotification($booking));
    }

    public function sendBookingCancelled(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingCancelledNotification($booking));
    }

    public function sendBookingRescheduled(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingRescheduledNotification($booking));
    }

    public function sendBookingCompleted(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingCompletedNotification($booking));
    }

    public function sendBookingNoShow(Booking $booking): void
    {
        $this->sendToUsers($booking, new BookingNoShowNotification($booking));
    }

    public function sendReminder(Booking $booking, int $hoursBefore): void
    {
        $this->sendToUsers($booking, new BookingReminderNotification($booking, $hoursBefore));
    }

    protected function sendToUsers(Booking $booking, $notification): void
    {
        $users = [$booking->student];

        if ($booking->teacher && $booking->teacher->user) {
            $users[] = $booking->teacher->user;
        }

        $notificationType = get_class($notification);

        foreach ($users as $user) {
            try {
                Notification::send($user, $notification);

                $this->logNotification($user, $booking, NotificationChannel::Email, 'sent', null, null, $notificationType);
            } catch (\Exception $e) {
                $this->logNotification($user, $booking, NotificationChannel::Email, 'failed', $e->getMessage(), null, $notificationType);
            }
        }
    }

    public function sendCourseEnrolledToStudent(\App\Models\Course $course, \App\Models\User $student): void
    {
        try {
            $notification = new \App\Notifications\CourseEnrolledNotification($course, $student);
            Notification::send($student, $notification);
            $this->logNotification($student, null, NotificationChannel::Email, 'sent', null, $course, get_class($notification));
        } catch (\Exception $e) {
            $this->logNotification($student, null, NotificationChannel::Email, 'failed', $e->getMessage(), $course, \App\Notifications\CourseEnrolledNotification::class);
        }
    }

    public function sendCourseEnrolledToTeacher(\App\Models\Course $course, \App\Models\User $student): void
    {
        try {
            $notification = new \App\Notifications\CourseEnrolledToTeacherNotification($course, $student);
            Notification::send($course->teacher, $notification);
            $this->logNotification($course->teacher, null, NotificationChannel::Email, 'sent', null, $course, get_class($notification));
        } catch (\Exception $e) {
            $this->logNotification($course->teacher, null, NotificationChannel::Email, 'failed', $e->getMessage(), $course, \App\Notifications\CourseEnrolledToTeacherNotification::class);
        }
    }

    protected function logNotification(
        \App\Models\User $user,
        ?Booking $booking,
        NotificationChannel $channel,
        string $status,
        ?string $errorMessage = null,
        ?\App\Models\Course $course = null,
        ?string $notificationType = null
    ): void {
        NotificationLog::create([
            'user_id' => $user->id,
            'booking_id' => $booking?->id,
            'course_id' => $course?->id,
            'channel' => $channel,
            'status' => $status,
            'payload' => [
                'notification_type' => $notificationType ?? 'unknown',
            ],
            'error_message' => $errorMessage,
        ]);
    }
}
