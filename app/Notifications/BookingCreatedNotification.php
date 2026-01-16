<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isStudent = $notifiable->id === $this->booking->student_id;
        $subject = $isStudent
            ? 'Your booking has been created'
            : 'New booking received';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($isStudent
                ? 'Your booking has been successfully created.'
                : 'You have received a new booking request.');

        $message->line("**Subject:** {$this->booking->subject->name}")
            ->line("**Teacher:** {$this->booking->teacher->user->name}")
            ->line("**Date & Time:** {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line("**Duration:** {$this->booking->start_at->diffInMinutes($this->booking->end_at)} minutes")
            ->line("**Mode:** {$this->booking->lesson_mode->label()}");

        if ($this->booking->isAwaitingPayment()) {
            $message->line('**Status:** Awaiting Payment')
                ->action('Complete Payment', route('student.bookings.pay', $this->booking));
        } else {
            $message->line("**Status:** {$this->booking->status->label()}");
        }

        if ($this->booking->location) {
            $message->line("**Location:** {$this->booking->location->name}");
        }

        return $message;
    }
}
