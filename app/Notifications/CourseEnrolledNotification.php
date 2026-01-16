<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseEnrolledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
        public User $student
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You now have access to {$this->course->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Congratulations! You now have access to the course **{$this->course->title}**.")
            ->line("**Subject:** {$this->course->subject->name}")
            ->line("**Teacher:** {$this->course->teacher->name}")
            ->line("**Price:** {$this->course->price} {$this->course->currency}")
            ->action('Start Learning', route('student.my-courses.learn', $this->course->slug))
            ->line('You can now access all lessons and start your learning journey!');
    }
}
