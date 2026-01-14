<?php

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
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
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.show', $booking))
        ->assertSuccessful();
});

it('prevents students from viewing other students bookings', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->otherStudent)
        ->get(route('student.bookings.show', $booking))
        ->assertForbidden();
});

it('allows teachers to view their own bookings', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.show', $booking))
        ->assertSuccessful();
});

it('allows students to view only available slots', function () {
    $availableSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $blockedSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Blocked,
        'start_at' => now()->addDay()->addHours(2),
        'end_at' => now()->addDay()->addHours(3),
    ]);

    $response = $this->actingAs($this->student)
        ->get(route('student.teachers.slots', $this->teacherProfile))
        ->assertSuccessful();

    // Check for time in different formats (12h or 24h)
    $availableTime12h = $availableSlot->start_at->format('g:i A');
    $availableTime24h = $availableSlot->start_at->format('H:i');
    $blockedTime12h = $blockedSlot->start_at->format('g:i A');
    $blockedTime24h = $blockedSlot->start_at->format('H:i');

    $content = $response->getContent();
    expect(str_contains($content, $availableTime12h) || str_contains($content, $availableTime24h))->toBeTrue();
    expect(str_contains($content, $blockedTime12h))->toBeFalse();
    expect(str_contains($content, $blockedTime24h))->toBeFalse();
});

it('allows students to cancel their own bookings', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
        'status' => BookingStatus::AwaitingPayment,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.cancel', $booking))
        ->assertRedirect();
});

it('prevents students from cancelling completed bookings', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'start_at' => now()->subDay(),
        'end_at' => now()->subDay()->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
        'status' => BookingStatus::Completed,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.cancel', $booking))
        ->assertForbidden();
});
