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
            ->subject(__('common.You now have access to :course', ['course' => $this->course->title]))
            ->greeting(__('common.Hello :name,', ['name' => $notifiable->name]))
            ->line(__('common.Congratulations! You now have access to the course **:course**.', ['course' => $this->course->title]))
            ->line(__('common.Subject:')." {$this->course->subject->name}")
            ->line(__('common.Teacher:')." {$this->course->teacher->name}")
            ->line(__('common.Price:')." {$this->course->price} {$this->course->currency}")
            ->action(__('common.Start Learning'), route('student.my-courses.learn', $this->course->slug))
            ->line(__('common.You can now access all lessons and start your learning journey!'));
    }
}
