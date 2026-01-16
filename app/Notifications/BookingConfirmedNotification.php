<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
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

        $message = (new MailMessage)
            ->subject(__('common.Booking Confirmed'))
            ->greeting(__('common.Hello :name,', ['name' => $notifiable->name]))
            ->line(__('common.Your booking has been confirmed!'));

        $message->line(__('common.Subject:')." {$this->booking->subject->name}")
            ->line(__('common.Teacher:')." {$this->booking->teacher->user->name}")
            ->line(__('common.Date & Time:')." {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line(__('common.Duration:')." {$this->booking->start_at->diffInMinutes($this->booking->end_at)} ".__('common.minutes'))
            ->line(__('common.Mode:')." {$this->booking->lesson_mode->label()}");

        if ($this->booking->lesson_mode->value === 'online' && $this->booking->meeting_url) {
            $message->line(__('common.Meeting Link:'))
                ->action(__('common.Join Meeting'), $this->booking->meeting_url);
        }

        if ($this->booking->location) {
            $message->line(__('common.Location:')." {$this->booking->location->name}");
        }

        return $message;
    }
}
