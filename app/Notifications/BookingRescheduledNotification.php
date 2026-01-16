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
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('common.Booking Rescheduled'))
            ->greeting(__('common.Hello :name,', ['name' => $notifiable->name]))
            ->line(__('common.Your booking has been rescheduled.'))
            ->line(__('common.Subject:')." {$this->booking->subject->name}")
            ->line(__('common.Teacher:')." {$this->booking->teacher->user->name}")
            ->line(__('common.New Date & Time:')." {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line(__('common.Duration:')." {$this->booking->start_at->diffInMinutes($this->booking->end_at)} ".__('common.minutes'))
            ->action(__('common.View Booking'), route('student.bookings.show', $this->booking));
    }
}
