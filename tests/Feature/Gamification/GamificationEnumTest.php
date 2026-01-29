<?php

use App\Enums\BookingStatus;
use App\Enums\SlotStatus;
use App\Models\Achievement;
use App\Models\Booking;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\UserAchievement;
use App\Services\Gamification\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $this->teacher = User::factory()->create();
    $this->teacher->assignRole('teacher');
    $this->teacherProfile = TeacherProfile::factory()->create(['user_id' => $this->teacher->id]);

    $this->student = User::factory()->create(['total_points' => 0, 'current_streak' => 0]);
    $this->student->assignRole('student');

    $this->subject = Subject::factory()->create();
    $this->teacherProfile->subjects()->attach($this->subject->id);

    $this->gamificationService = app(GamificationService::class);

    // Seed default achievements
    $this->gamificationService->seedAchievements();
    $this->gamificationService->seedBadges();
});

it('counts completed bookings correctly using enum', function () {
    // Create bookings with different statuses using ENUM values
    $slot1 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(1),
        'end_at' => now()->addDays(1)->addHour(),
    ]);

    $slot2 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(2),
        'end_at' => now()->addDays(2)->addHour(),
    ]);

    $slot3 = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->addDays(3),
        'end_at' => now()->addDays(3)->addHour(),
    ]);

    // Create completed booking
    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot1->id,
        'status' => BookingStatus::Completed,
    ]);

    // Create cancelled booking - should NOT count
    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot2->id,
        'status' => BookingStatus::Cancelled,
    ]);

    // Create confirmed booking - should NOT count as completed
    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot3->id,
        'status' => BookingStatus::Confirmed,
    ]);

    // Check achievements - the booking_count type should only count completed
    $this->gamificationService->checkAchievements($this->student);

    // Get the "First Steps" achievement (1 completed booking)
    $firstSteps = Achievement::where('slug', 'first-steps')->first();
    $userAchievement = UserAchievement::where('user_id', $this->student->id)
        ->where('achievement_id', $firstSteps->id)
        ->first();

    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->progress)->toBe(1); // Only 1 completed booking
    expect($userAchievement->isUnlocked())->toBeTrue(); // Threshold is 1
});

it('calculates perfect attendance correctly using enum', function () {
    // Create 5 completed bookings in sequence
    for ($i = 0; $i < 5; $i++) {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'status' => SlotStatus::Booked,
            'start_at' => now()->subDays(5 - $i),
        ]);

        Booking::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'time_slot_id' => $slot->id,
            'status' => BookingStatus::Completed,
            'start_at' => now()->subDays(5 - $i),
        ]);
    }

    $this->gamificationService->checkAchievements($this->student);

    // Get the "Perfect Attendance" achievement (10 completed without no-shows)
    $perfectAttendance = Achievement::where('slug', 'perfect-attendance')->first();
    $userAchievement = UserAchievement::where('user_id', $this->student->id)
        ->where('achievement_id', $perfectAttendance->id)
        ->first();

    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->progress)->toBe(5); // 5 consecutive completed
});

it('breaks perfect attendance streak on no-show using enum', function () {
    // Create 3 completed, then 1 no-show, then 2 completed
    $dates = [5, 4, 3, 2, 1, 0]; // Days ago
    $statuses = [
        BookingStatus::Completed,
        BookingStatus::Completed,
        BookingStatus::NoShow, // This breaks the streak
        BookingStatus::Completed,
        BookingStatus::Completed,
        BookingStatus::Completed,
    ];

    foreach ($dates as $index => $daysAgo) {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'status' => SlotStatus::Booked,
            'start_at' => now()->subDays($daysAgo),
        ]);

        Booking::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'time_slot_id' => $slot->id,
            'status' => $statuses[$index],
            'start_at' => now()->subDays($daysAgo),
        ]);
    }

    $this->gamificationService->checkAchievements($this->student);

    // The perfect attendance should be 3 (the most recent completed before no-show)
    $perfectAttendance = Achievement::where('slug', 'perfect-attendance')->first();
    $userAchievement = UserAchievement::where('user_id', $this->student->id)
        ->where('achievement_id', $perfectAttendance->id)
        ->first();

    expect($userAchievement)->not->toBeNull();
    // The most recent bookings are: Completed, Completed, Completed (3), then NoShow breaks
    expect($userAchievement->progress)->toBe(3);
});

it('counts early bookings correctly using enum', function () {
    // Create early morning bookings (before 9 AM)
    for ($i = 0; $i < 3; $i++) {
        $slot = TimeSlot::factory()->create([
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'status' => SlotStatus::Booked,
            'start_at' => now()->subDays($i)->setTime(8, 0), // 8 AM
        ]);

        Booking::factory()->create([
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacherProfile->id,
            'subject_id' => $this->subject->id,
            'time_slot_id' => $slot->id,
            'status' => BookingStatus::Completed,
            'start_at' => now()->subDays($i)->setTime(8, 0),
        ]);
    }

    // Create a late booking (after 9 AM) - should not count
    $lateSlot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
        'start_at' => now()->setTime(10, 0),
    ]);

    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $lateSlot->id,
        'status' => BookingStatus::Completed,
        'start_at' => now()->setTime(10, 0),
    ]);

    $this->gamificationService->checkAchievements($this->student);

    // Get the "Early Bird" achievement (5 early bookings)
    $earlyBird = Achievement::where('slug', 'early-bird')->first();
    $userAchievement = UserAchievement::where('user_id', $this->student->id)
        ->where('achievement_id', $earlyBird->id)
        ->first();

    expect($userAchievement)->not->toBeNull();
    expect($userAchievement->progress)->toBe(3); // Only 3 early bookings
});

it('awards points and updates leaderboard', function () {
    $initialPoints = $this->student->total_points;

    $this->gamificationService->awardPoints(
        $this->student,
        100,
        'test',
        'Test points award'
    );

    $this->student->refresh();

    expect($this->student->total_points)->toBe($initialPoints + 100);

    // Check leaderboard entry was created
    $leaderboardEntry = $this->student->leaderboardEntries()
        ->where('year', now()->year)
        ->where('month', now()->month)
        ->first();

    expect($leaderboardEntry)->not->toBeNull();
    expect($leaderboardEntry->points)->toBe(100);
});

it('unlocks achievement and awards bonus points', function () {
    // Create exactly 1 completed booking to unlock "First Steps"
    $slot = TimeSlot::factory()->create([
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'status' => SlotStatus::Booked,
    ]);

    Booking::factory()->create([
        'student_id' => $this->student->id,
        'teacher_id' => $this->teacherProfile->id,
        'subject_id' => $this->subject->id,
        'time_slot_id' => $slot->id,
        'status' => BookingStatus::Completed,
    ]);

    $this->gamificationService->checkAchievements($this->student);

    $this->student->refresh();

    // First Steps achievement gives 50 points
    expect($this->student->total_points)->toBe(50);

    // Achievement should be unlocked
    $firstSteps = Achievement::where('slug', 'first-steps')->first();
    $userAchievement = UserAchievement::where('user_id', $this->student->id)
        ->where('achievement_id', $firstSteps->id)
        ->first();

    expect($userAchievement->isUnlocked())->toBeTrue();
});
