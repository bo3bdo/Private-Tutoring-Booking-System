<?php

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingNoShowNotification;
use App\Notifications\BookingReminderNotification;
use App\Notifications\BookingRescheduledNotification;
use App\Notifications\CourseEnrolledNotification;
use App\Notifications\CourseEnrolledToTeacherNotification;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $this->slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->slot->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => $this->slot->start_at,
        'end_at' => $this->slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->notificationService = app(NotificationService::class);
});

it('sends booking created notification to student and teacher', function () {
    Notification::fake();

    $this->notificationService->sendBookingCreated($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingCreatedNotification::class
    );
});

it('sends booking confirmed notification', function () {
    Notification::fake();

    $this->notificationService->sendBookingConfirmed($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingConfirmedNotification::class
    );
});

it('sends booking cancelled notification', function () {
    Notification::fake();

    $this->notificationService->sendBookingCancelled($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingCancelledNotification::class
    );
});

it('sends booking rescheduled notification', function () {
    Notification::fake();

    $this->notificationService->sendBookingRescheduled($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingRescheduledNotification::class
    );
});

it('sends booking completed notification', function () {
    Notification::fake();

    $this->notificationService->sendBookingCompleted($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingCompletedNotification::class
    );
});

it('sends booking no show notification', function () {
    Notification::fake();

    $this->notificationService->sendBookingNoShow($this->booking);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingNoShowNotification::class
    );
});

it('sends booking reminder notification', function () {
    Notification::fake();

    $this->notificationService->sendReminder($this->booking, 24);

    Notification::assertSentTo(
        [$this->student, $this->teacher],
        BookingReminderNotification::class
    );
});

it('sends course enrolled notification to student', function () {
    Notification::fake();

    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'is_published' => true,
    ]);

    $this->notificationService->sendCourseEnrolledToStudent($course, $this->student);

    Notification::assertSentTo(
        $this->student,
        CourseEnrolledNotification::class
    );
});

it('sends course enrolled notification to teacher', function () {
    Notification::fake();

    $course = Course::factory()->create([
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'is_published' => true,
    ]);

    $this->notificationService->sendCourseEnrolledToTeacher($course, $this->student);

    Notification::assertSentTo(
        $this->teacher,
        CourseEnrolledToTeacherNotification::class
    );
});

it('logs notification attempts', function () {
    $this->notificationService->sendBookingCreated($this->booking);

    $this->assertDatabaseHas('notification_logs', [
        'user_id' => $this->student->id,
        'booking_id' => $this->booking->id,
        'channel' => 'email',
        'status' => 'sent',
    ]);
});
