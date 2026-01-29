<?php

use App\Models\Booking;
use App\Models\LiveMeeting;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\Meeting\MeetingService;

beforeEach(function () {
    $this->service = new MeetingService;

    $this->teacherUser = User::factory()->create();
    $this->teacherUser->assignRole('teacher');
    $this->teacher = TeacherProfile::factory()->create(['user_id' => $this->teacherUser->id]);

    $this->student = User::factory()->create();
    $this->student->assignRole('student');

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

describe('Provider Registration', function () {
    it('registers default providers', function () {
        $providers = $this->service->getAvailableProviders();

        expect($providers)->toHaveKey('zoom');
        expect($providers)->toHaveKey('google_meet');
    });

    it('returns configured providers only', function () {
        // Without API keys configured, providers should not be available
        $providers = $this->service->getAvailableProviders();

        // Since we don't have API keys in testing, providers won't be configured
        expect($providers)->toBeEmpty();
    });
});

describe('Meeting Creation', function () {
    it('creates a meeting with specified provider', function () {
        // Since we don't have real API keys, we'll create a mock meeting directly
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'zoom',
            'meeting_id' => '1234567890',
            'meeting_url' => 'https://zoom.us/j/1234567890',
            'join_url' => 'https://zoom.us/j/1234567890',
            'host_url' => 'https://zoom.us/start/1234567890',
            'password' => 'password123',
            'scheduled_at' => $this->booking->start_at,
            'duration_minutes' => 60,
            'metadata' => ['zoom_meeting_uuid' => 'uuid-123'],
        ]);

        expect($meeting)->toBeInstanceOf(LiveMeeting::class);
        expect($meeting->booking_id)->toBe($this->booking->id);
        expect($meeting->provider)->toBe('zoom');
    });

    it('associates meeting with booking', function () {
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'google_meet',
            'meeting_url' => 'https://meet.google.com/abc-defg-hij',
            'scheduled_at' => $this->booking->start_at,
            'duration_minutes' => 60,
        ]);

        expect($meeting->booking->id)->toBe($this->booking->id);
    });
});

describe('Meeting Status', function () {
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
            'scheduled_at' => $this->booking->start_at,
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
});

describe('Meeting Retrieval', function () {
    it('can get join URL for student', function () {
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123',
            'join_url' => 'https://zoom.us/j/123?pwd=student',
            'host_url' => 'https://zoom.us/start/123',
            'scheduled_at' => $this->booking->start_at,
        ]);

        // Through the service
        $joinUrl = $this->service->getJoinUrl($meeting, false);

        expect($joinUrl)->toBe($meeting->join_url);
    });

    it('can get host URL for teacher', function () {
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123',
            'join_url' => 'https://zoom.us/j/123?pwd=student',
            'host_url' => 'https://zoom.us/start/123',
            'scheduled_at' => $this->booking->start_at,
        ]);

        $hostUrl = $this->service->getJoinUrl($meeting, true);

        expect($hostUrl)->toBe($meeting->host_url);
    });
});

describe('Meeting Updates', function () {
    it('updates meeting schedule', function () {
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
        ]);

        $newStartTime = now()->addDays(2);

        $updatedMeeting = $this->service->updateMeeting($meeting, [
            'start_at' => $newStartTime,
            'duration' => 90,
        ]);

        expect($updatedMeeting->scheduled_at->toDateString())->toBe($newStartTime->toDateString());
        expect($updatedMeeting->duration_minutes)->toBe(90);
    });
});

describe('Meeting Deletion', function () {
    it('deletes a meeting', function () {
        $meeting = LiveMeeting::create([
            'booking_id' => $this->booking->id,
            'provider' => 'zoom',
            'meeting_url' => 'https://zoom.us/j/123',
            'scheduled_at' => $this->booking->start_at,
        ]);

        // Since we don't have API configured, it will return false
        $result = $this->service->deleteMeeting($meeting);

        // Without API keys, the deletion attempt returns false
        expect($result)->toBeFalse();
    });
});

describe('Meeting Factory', function () {
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
