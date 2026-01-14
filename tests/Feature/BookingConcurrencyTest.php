<?php

use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student1 = User::factory()->create();
    $this->student1->assignRole('student');

    $this->student2 = User::factory()->create();
    $this->student2->assignRole('student');

    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $this->slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Available,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->bookingService = app(BookingService::class);
});

it('prevents double booking of the same slot', function () {
    $booking1 = $this->bookingService->createBooking(
        student: $this->student1,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    expect($booking1)->toBeInstanceOf(Booking::class);
    expect($this->slot->fresh()->status)->toBe(SlotStatus::Booked);

    expect(function () {
        $this->bookingService->createBooking(
            student: $this->student2,
            timeSlot: $this->slot->fresh(),
            subjectId: $this->subject->id,
            lessonMode: LessonMode::Online->value,
            meetingUrl: 'https://meet.example.com/test2'
        );
    })->toThrow(\Exception::class, 'This slot is no longer available');
});

it('ensures only one booking per time slot', function () {
    $booking = $this->bookingService->createBooking(
        student: $this->student1,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    $count = Booking::where('time_slot_id', $this->slot->id)->count();
    expect($count)->toBe(1);
});

it('releases slot when booking is cancelled', function () {
    $booking = $this->bookingService->createBooking(
        student: $this->student1,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    expect($this->slot->fresh()->status)->toBe(SlotStatus::Booked);

    $this->bookingService->cancelBooking($booking, $this->student1);

    expect($this->slot->fresh()->status)->toBe(SlotStatus::Available);
});
