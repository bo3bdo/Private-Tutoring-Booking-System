<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public int $hoursBefore
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $timeText = $this->hoursBefore === 1 ? '1 hour' : "{$this->hoursBefore} hours";

        $message = (new MailMessage)
            ->subject("Reminder: Lesson in {$timeText}")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a reminder that you have a lesson in {$timeText}.")
            ->line("**Subject:** {$this->booking->subject->name}")
            ->line("**Teacher:** {$this->booking->teacher->user->name}")
            ->line("**Date & Time:** {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line("**Duration:** {$this->booking->start_at->diffInMinutes($this->booking->end_at)} minutes")
            ->line("**Mode:** {$this->booking->lesson_mode->label()}");

        if ($this->booking->lesson_mode->value === 'online' && $this->booking->meeting_url) {
            $message->action('Join Meeting', $this->booking->meeting_url);
        }

        if ($this->booking->location) {
            $message->line("**Location:** {$this->booking->location->name}");
        }

        return $message;
    }
}
