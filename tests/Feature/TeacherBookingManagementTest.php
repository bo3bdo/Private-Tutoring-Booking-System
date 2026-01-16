<?php

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Location;
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
    ]);

    $this->location = Location::factory()->create(['is_active' => true]);
});

it('allows teacher to view bookings list', function () {
    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.index'))
        ->assertSuccessful()
        ->assertSee($this->student->name)
        ->assertSee($this->subject->name);
});

it('allows teacher to filter bookings by status', function () {
    $slot2 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot2->id,
        'status' => BookingStatus::Confirmed,
        'start_at' => $slot2->start_at,
        'end_at' => $slot2->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.index', ['filter' => 'upcoming']))
        ->assertSuccessful();

    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.index', ['filter' => 'confirmed']))
        ->assertSuccessful();
});

it('allows teacher to view booking details', function () {
    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.show', $this->booking))
        ->assertSuccessful()
        ->assertSee($this->student->name)
        ->assertSee($this->subject->name);
});

it('allows teacher to mark booking as completed', function () {
    Notification::fake();

    $this->actingAs($this->teacher)
        ->post(route('teacher.bookings.status', $this->booking), [
            'status' => 'completed',
        ])
        ->assertRedirect();

    expect($this->booking->fresh()->status)->toBe(BookingStatus::Completed);

    $this->assertDatabaseHas('booking_histories', [
        'booking_id' => $this->booking->id,
        'action' => 'status_changed',
    ]);
});

it('allows teacher to mark booking as no show', function () {
    Notification::fake();

    $this->actingAs($this->teacher)
        ->post(route('teacher.bookings.status', $this->booking), [
            'status' => 'no_show',
        ])
        ->assertRedirect();

    expect($this->booking->fresh()->status)->toBe(BookingStatus::NoShow);

    $this->assertDatabaseHas('booking_histories', [
        'booking_id' => $this->booking->id,
        'action' => 'status_changed',
    ]);
});

it('allows teacher to update meeting URL for online booking', function () {
    $onlineSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    $onlineBooking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $onlineSlot->id,
        'lesson_mode' => LessonMode::Online->value,
        'status' => BookingStatus::Confirmed,
        'start_at' => $onlineSlot->start_at,
        'end_at' => $onlineSlot->end_at,
    ]);

    $this->actingAs($this->teacher)
        ->patch(route('teacher.bookings.update-meeting-url', $onlineBooking), [
            'meeting_url' => 'https://meet.google.com/test-meeting',
        ])
        ->assertRedirect();

    expect($onlineBooking->fresh()->meeting_url)->toBe('https://meet.google.com/test-meeting');

    $this->assertDatabaseHas('booking_histories', [
        'booking_id' => $onlineBooking->id,
        'action' => 'meeting_url_updated',
    ]);
});

it('allows teacher to update location for in-person booking', function () {
    $inPersonSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(3),
        'end_at' => now()->addDays(3)->addHour(),
    ]);

    $inPersonBooking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $inPersonSlot->id,
        'lesson_mode' => LessonMode::InPerson->value,
        'status' => BookingStatus::Confirmed,
        'start_at' => $inPersonSlot->start_at,
        'end_at' => $inPersonSlot->end_at,
    ]);

    $this->actingAs($this->teacher)
        ->patch(route('teacher.bookings.update-location', $inPersonBooking), [
            'location_id' => $this->location->id,
        ])
        ->assertRedirect();

    expect($inPersonBooking->fresh()->location_id)->toBe($this->location->id);

    $this->assertDatabaseHas('booking_histories', [
        'booking_id' => $inPersonBooking->id,
        'action' => 'location_updated',
    ]);
});

it('allows teacher to reschedule booking', function () {
    Notification::fake();

    $newSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    $this->actingAs($this->teacher)
        ->post(route('teacher.bookings.reschedule', $this->booking), [
            'time_slot_id' => $newSlot->id,
        ])
        ->assertRedirect();

    expect($this->booking->fresh()->time_slot_id)->toBe($newSlot->id);
    expect($this->booking->fresh()->start_at->format('Y-m-d'))->toBe($newSlot->start_at->format('Y-m-d'));

    $this->assertDatabaseHas('booking_histories', [
        'booking_id' => $this->booking->id,
        'action' => 'rescheduled',
    ]);
});

it('allows teacher to cancel booking with reason', function () {
    Notification::fake();

    $this->actingAs($this->teacher)
        ->post(route('teacher.bookings.cancel', $this->booking), [
            'cancellation_reason' => 'Teacher unavailable',
        ])
        ->assertRedirect();

    expect($this->booking->fresh()->status)->toBe(BookingStatus::Cancelled);
    expect($this->booking->fresh()->cancellation_reason)->toBe('Teacher unavailable');
    expect($this->slot->fresh()->status)->toBe(SlotStatus::Available);
});

it('prevents teacher from viewing other teachers bookings', function () {
    $otherTeacher = User::factory()->create();
    $otherTeacher->assignRole('teacher');
    $otherTeacherProfile = TeacherProfile::factory()->create(['user_id' => $otherTeacher->id]);

    $otherSlot = TimeSlot::factory()->create([
        'teacher_id' => $otherTeacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(4),
        'end_at' => now()->addDays(4)->addHour(),
    ]);

    $otherBooking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $otherTeacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $otherSlot->id,
        'start_at' => $otherSlot->start_at,
        'end_at' => $otherSlot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->teacher)
        ->get(route('teacher.bookings.show', $otherBooking))
        ->assertForbidden();
});

it('prevents teacher from updating booking status for non-confirmed booking', function () {
    $cancelledSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDays(5),
        'end_at' => now()->addDays(5)->addHour(),
    ]);

    $cancelledBooking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $cancelledSlot->id,
        'status' => BookingStatus::Cancelled,
        'start_at' => $cancelledSlot->start_at,
        'end_at' => $cancelledSlot->end_at,
        'lesson_mode' => LessonMode::Online,
    ]);

    $this->actingAs($this->teacher)
        ->post(route('teacher.bookings.status', $cancelledBooking), [
            'status' => 'completed',
        ])
        ->assertForbidden();
});
