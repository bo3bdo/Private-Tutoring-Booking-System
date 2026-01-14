<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseEnrolledToTeacherNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
        public User $student
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New student enrolled: {$this->student->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new student has enrolled in your course **{$this->course->title}**.")
            ->line("**Student:** {$this->student->name} ({$this->student->email})")
            ->line("**Subject:** {$this->course->subject->name}")
            ->action('View Course Sales', route('teacher.courses.sales', $this->course))
            ->line("Thank you for sharing your knowledge!");
    }
}
