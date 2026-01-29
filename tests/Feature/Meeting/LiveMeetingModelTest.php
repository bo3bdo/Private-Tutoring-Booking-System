<?php

use App\Models\Booking;
use App\Models\LiveMeeting;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->teacherUser = User::factory()->create();
    $this->teacher = TeacherProfile::factory()->create(['user_id' => $this->teacherUser->id]);

    $this->student = User::factory()->create();

    $this->subject = Subject::factory()->create();

    $this->timeSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacher->id,
        'start_at' => now()->addDay(),
        'end_at' => now()->addDay()->addHour(),
    ]);

    $this->booking = Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacher->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $this->timeSlot->id,
        'start_at' => $this->timeSlot->start_at,
        'end_at' => $this->timeSlot->end_at,
    ]);
});

it('can create live meeting', function () {
    $meeting = LiveMeeting::create([
        'booking_id' => $this->booking->id,
        'provider' => 'zoom',
        'meeting_id' => '1234567890',
        'meeting_url' => 'https://zoom.us/j/1234567890',
        'join_url' => 'https://zoom.us/j/1234567890?pwd=abc',
        'host_url' => 'https://zoom.us/start/1234567890',
        'password' => 'meeting123',
        'scheduled_at' => $this->booking->start_at,
        'duration_minutes' => 60,
        'metadata' => ['zoom_meeting_uuid' => 'uuid-123'],
    ]);

    expect($meeting->provider)->toBe('zoom');
    expect($meeting->meeting_id)->toBe('1234567890');
    expect($meeting->duration_minutes)->toBe(60);
    expect($meeting->metadata)->toHaveKey('zoom_meeting_uuid');
});

it('belongs to booking', function () {
    $meeting = LiveMeeting::create([
        'booking_id' => $this->booking->id,
        'provider' => 'zoom',
        'meeting_url' => 'https://zoom.us/j/123',
        'scheduled_at' => $this->booking->start_at,
    ]);

    expect($meeting->booking->id)->toBe($this->booking->id);
});

it('determines if meeting is active', function () {
    $meeting = LiveMeeting::create([
        'booking_id' => $this->booking->id,
        'provider' => 'zoom',
        'meeting_url' => 'https://zoom.us/j/123',
        'scheduled_at' => $this->booking->start_at,
        'started_at' => now(),
    ]);

    expect($meeting->isActive())->toBeTrue();
});

it('determines if meeting is completed', function () {
    $meeting = LiveMeeting::create([
        'booking_id' => $this->booking->id,
        'provider' => 'zoom',
        'meeting_url' => 'https://zoom.us/j/123',
        'scheduled_at' => now()->subDay(),
        'started_at' => now()->subHour(),
        'ended_at' => now(),
    ]);

    expect($meeting->isCompleted())->toBeTrue();
    expect($meeting->isActive())->toBeFalse();
});

it('determines if meeting is upcoming', function () {
    $meeting = LiveMeeting::create([
        'booking_id' => $this->booking->id,
        'provider' => 'zoom',
        'meeting_url' => 'https://zoom.us/j/123',
        'scheduled_at' => now()->addDay(),
    ]);

    expect($meeting->isUpcoming())->toBeTrue();
});

describe('Factory', function () {
    it('creates meeting with factory', function () {
        $meeting = LiveMeeting::factory()->create();

        expect($meeting)->toBeInstanceOf(LiveMeeting::class);
        expect($meeting->meeting_url)->not->toBeNull();
    });

    it('creates started meeting with factory', function () {
        $meeting = LiveMeeting::factory()->started()->create();

        expect($meeting->started_at)->not->toBeNull();
        expect($meeting->isActive())->toBeTrue();
    });

    it('creates completed meeting with factory', function () {
        $meeting = LiveMeeting::factory()->completed()->create();

        expect($meeting->started_at)->not->toBeNull();
        expect($meeting->ended_at)->not->toBeNull();
        expect($meeting->isCompleted())->toBeTrue();
    });
});
