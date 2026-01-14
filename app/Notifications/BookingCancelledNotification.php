<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isStudent = $notifiable->id === $this->booking->student_id;

        return (new MailMessage)
            ->subject("Booking Cancelled")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your booking has been cancelled.")
            ->line("**Subject:** {$this->booking->subject->name}")
            ->line("**Teacher:** {$this->booking->teacher->user->name}")
            ->line("**Date & Time:** {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line("If you have any questions, please contact us.");
    }
}
