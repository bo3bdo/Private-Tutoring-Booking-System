<?php

use App\Enums\BookingStatus;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->otherStudent = User::factory()->create();
    $this->otherStudent->assignRole('student');

    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);
});

it('allows students to view their own bookings', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.show', $booking))
        ->assertSuccessful();
});

it('prevents students from viewing other students bookings', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
    ]);

    $this->actingAs($this->otherStudent)
        ->get(route('student.bookings.show', $booking))
        ->assertForbidden();
});

it('allows teachers to view their own bookings', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.show', $booking))
        ->assertSuccessful();
});

it('allows students to view only available slots', function () {
    $availableSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'status' => SlotStatus::Available,
    ]);

    $blockedSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'status' => SlotStatus::Blocked,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.teachers.slots', $this->teacherProfile))
        ->assertSuccessful()
        ->assertSee($availableSlot->start_at->format('H:i'))
        ->assertDontSee($blockedSlot->start_at->format('H:i'));
});

it('allows students to cancel their own bookings', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => BookingStatus::AwaitingPayment,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.cancel', $booking))
        ->assertRedirect();
});

it('prevents students from cancelling completed bookings', function () {
    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => BookingStatus::Completed,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.cancel', $booking))
        ->assertForbidden();
});
