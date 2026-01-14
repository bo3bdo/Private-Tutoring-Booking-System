<?php

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use App\Enums\SlotStatus;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
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

    $this->bookingService = app(BookingService::class);
});

it('creates booking with awaiting payment status when payment required', function () {
    \App\Models\Setting::set('payment_required', true);

    $booking = $this->bookingService->createBooking(
        student: $this->student,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    expect($booking->status)->toBe(BookingStatus::AwaitingPayment);
    expect($booking->timeSlot->status)->toBe(SlotStatus::Booked);
});

it('creates booking with confirmed status when payment not required', function () {
    \App\Models\Setting::set('payment_required', false);

    $booking = $this->bookingService->createBooking(
        student: $this->student,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    expect($booking->status)->toBe(BookingStatus::Confirmed);
});

it('logs booking history when booking is created', function () {
    $booking = $this->bookingService->createBooking(
        student: $this->student,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    $history = BookingHistory::where('booking_id', $booking->id)
        ->where('action', 'created')
        ->first();

    expect($history)->not->toBeNull();
    expect($history->new_status)->toBe($booking->status->value);
});

it('updates booking status correctly', function () {
    $booking = $this->bookingService->createBooking(
        student: $this->student,
        timeSlot: $this->slot,
        subjectId: $this->subject->id,
        lessonMode: LessonMode::Online->value,
        meetingUrl: 'https://meet.example.com/test'
    );

    $this->bookingService->updateStatus($booking, BookingStatus::Completed, $this->teacher);

    expect($booking->fresh()->status)->toBe(BookingStatus::Completed);
    expect($booking->fresh()->completed_at)->not->toBeNull();
});
