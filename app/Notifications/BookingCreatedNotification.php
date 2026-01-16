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
            ? __('common.Your booking has been created')
            : __('common.New booking received');

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting(__('common.Hello :name,', ['name' => $notifiable->name]))
            ->line($isStudent
                ? __('common.Your booking has been successfully created.')
                : __('common.You have received a new booking request.'));

        $message->line(__('common.Subject:')." {$this->booking->subject->name}")
            ->line(__('common.Teacher:')." {$this->booking->teacher->user->name}")
            ->line(__('common.Date & Time:')." {$this->booking->start_at->format('l, F j, Y \a\t g:i A')}")
            ->line(__('common.Duration:')." {$this->booking->start_at->diffInMinutes($this->booking->end_at)} ".__('common.minutes'))
            ->line(__('common.Mode:')." {$this->booking->lesson_mode->label()}");

        if ($this->booking->isAwaitingPayment()) {
            $message->line(__('common.Status:').' '.__('common.Awaiting Payment'))
                ->action(__('common.Complete Payment'), route('student.bookings.pay', $this->booking));
        } else {
            $message->line(__('common.Status:')." {$this->booking->status->label()}");
        }

        if ($this->booking->location) {
            $message->line(__('common.Location:')." {$this->booking->location->name}");
        }

        return $message;
    }
}
