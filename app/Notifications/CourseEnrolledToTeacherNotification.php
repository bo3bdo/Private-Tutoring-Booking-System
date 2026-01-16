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
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('common.New student enrolled: :student', ['student' => $this->student->name]))
            ->greeting(__('common.Hello :name,', ['name' => $notifiable->name]))
            ->line(__('common.A new student has enrolled in your course **:course**.', ['course' => $this->course->title]))
            ->line(__('common.Student:')." {$this->student->name} ({$this->student->email})")
            ->line(__('common.Subject:')." {$this->course->subject->name}")
            ->action(__('common.View Course Sales'), route('teacher.courses.sales', $this->course))
            ->line(__('common.Thank you for sharing your knowledge!'));
    }
}
