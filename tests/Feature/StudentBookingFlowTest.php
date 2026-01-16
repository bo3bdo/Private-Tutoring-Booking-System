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
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create([
        'user_id' => $this->teacher->id,
        'hourly_rate' => 25.00,
    ]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

    $this->subject = Subject::factory()->create(['is_active' => true]);
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $this->slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);
});

it('allows student to browse subjects', function () {
    $this->actingAs($this->student)
        ->get(route('student.subjects.index'))
        ->assertSuccessful()
        ->assertSee($this->subject->name);
});

it('allows student to view subject details with teachers', function () {
    $this->actingAs($this->student)
        ->get(route('student.subjects.show', $this->subject))
        ->assertSuccessful()
        ->assertSee($this->subject->name)
        ->assertSee($this->teacher->name);
});

it('allows student to view available slots for a teacher', function () {
    $this->actingAs($this->student)
        ->get(route('student.teachers.slots', [
            'teacher' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
        ]))
        ->assertSuccessful()
        ->assertSee($this->teacher->name);
});

it('allows student to create booking request', function () {
    Notification::fake();

    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(1)->addHours(2),
        'end_at' => now()->addDays(1)->addHours(3),
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.create', $slot))
        ->assertSuccessful();

    $this->actingAs($this->student)
        ->post(route('student.bookings.store'), [
            'time_slot_id' => $slot->id,
            'subject_id' => $this->subject->id,
            'lesson_mode' => LessonMode::Online->value,
            'notes' => 'Test booking notes',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('bookings', [
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'lesson_mode' => LessonMode::Online->value,
        'status' => BookingStatus::AwaitingPayment->value,
    ]);

    expect($slot->fresh()->status)->toBe(SlotStatus::Booked);
});

it('redirects to payment page when booking requires payment', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(3),
        'end_at' => now()->addDays(3)->addHour(),
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.store'), [
            'time_slot_id' => $slot->id,
            'subject_id' => $this->subject->id,
            'lesson_mode' => LessonMode::Online->value,
        ])
        ->assertRedirect(route('student.bookings.pay', Booking::latest()->first()));
});

it('allows student to view booking payment page', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::AwaitingPayment,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.pay', $booking))
        ->assertSuccessful()
        ->assertSee($this->subject->name)
        ->assertSee($this->teacher->name);
});

it('allows student to cancel booking with reason', function () {
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(6),
        'end_at' => now()->addDays(6)->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.cancel', $booking), [
            'cancellation_reason' => 'Need to reschedule',
        ])
        ->assertRedirect(route('student.bookings.index'));

    expect($booking->fresh()->status)->toBe(BookingStatus::Cancelled);
    expect($booking->fresh()->cancellation_reason)->toBe('Need to reschedule');
    expect($slot->fresh()->status)->toBe(SlotStatus::Available);
});

it('prevents student from viewing other students bookings', function () {
    $otherStudent = User::factory()->create();
    $otherStudent->assignRole('student');

    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(4),
        'end_at' => now()->addDays(4)->addHour(),
    ]);

    $booking = Booking::factory()->create([
        'student_id' => $otherStudent->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'start_at' => $slot->start_at,
        'end_at' => $slot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.show', $booking))
        ->assertForbidden();
});

it('allows student to filter bookings by status', function () {
    $slot1 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(5),
        'end_at' => now()->addDays(5)->addHour(),
    ]);

    $slot2 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->subDays(2),
        'end_at' => now()->subDays(2)->addHour(),
    ]);

    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot1->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => $slot1->start_at,
        'end_at' => $slot1->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot2->id,
        'status' => BookingStatus::Cancelled,
        'start_at' => $slot2->start_at,
        'end_at' => $slot2->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->student)
        ->get(route('student.bookings.index', ['filter' => 'upcoming']))
        ->assertSuccessful();

    $this->actingAs($this->student)
        ->get(route('student.bookings.index', ['filter' => 'past']))
        ->assertSuccessful();

    $this->actingAs($this->student)
        ->get(route('student.bookings.index', ['filter' => 'cancelled']))
        ->assertSuccessful();
});

it('prevents student from creating booking for unavailable slot', function () {
    $blockedSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Blocked,
        'start_at' => now()->addDays(1)->addHours(4),
        'end_at' => now()->addDays(1)->addHours(5),
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.store'), [
            'time_slot_id' => $blockedSlot->id,
            'subject_id' => $this->subject->id,
            'lesson_mode' => LessonMode::Online->value,
        ])
        ->assertForbidden();
});

it('prevents student from creating booking for past slot', function () {
    $pastSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->subDays(2),
        'end_at' => now()->subDays(2)->addHour(),
    ]);

    $this->actingAs($this->student)
        ->post(route('student.bookings.store'), [
            'time_slot_id' => $pastSlot->id,
            'subject_id' => $this->subject->id,
            'lesson_mode' => LessonMode::Online->value,
        ])
        ->assertForbidden();
});
