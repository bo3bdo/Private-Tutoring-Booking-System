<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRescheduledNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject("Booking Rescheduled")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your booking has been rescheduled.")
            ->line("**Subject:** {$this->booking->subject->name}")
            ->line("**Teacher:** {$this->booking->teacher->user->name}")
            ->line("**New Date & Time:** {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line("**Duration:** {$this->booking->start_at->diffInMinutes($this->booking->end_at)} minutes")
            ->action('View Booking', route('student.bookings.show', $this->booking));
    }
}
