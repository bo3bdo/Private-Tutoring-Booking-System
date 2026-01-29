<?php

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Booking;
use App\Models\LeaderboardEntry;
use App\Models\PointsHistory;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\UserBadge;
use App\Services\Gamification\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new GamificationService;
    $this->user = User::factory()->create();
});

describe('Points System', function () {
    it('awards points to user', function () {
        $history = $this->service->awardPoints(
            $this->user,
            100,
            'booking',
            'Completed a booking'
        );

        expect($history)->toBeInstanceOf(PointsHistory::class);
        expect($history->points)->toBe(100);
        expect($this->user->fresh()->total_points)->toBe(100);
    });

    it('tracks points history', function () {
        $this->service->awardPoints($this->user, 100, 'booking', 'First booking');
        $this->service->awardPoints($this->user, 50, 'review', 'Left a review');

        expect(PointsHistory::where('user_id', $this->user->id)->count())->toBe(2);
        expect(PointsHistory::where('user_id', $this->user->id)->sum('points'))->toBe(150);
    });

    it('allows spending points', function () {
        $this->service->awardPoints($this->user, 100, 'bonus', 'Welcome bonus');

        $history = $this->service->spendPoints($this->user, 50, 'Discount redemption');

        expect($history)->not->toBeNull();
        expect($history->points)->toBe(-50);
        expect($this->user->fresh()->total_points)->toBe(50);
    });

    it('prevents spending more points than available', function () {
        $this->service->awardPoints($this->user, 50, 'bonus', 'Welcome bonus');

        $history = $this->service->spendPoints($this->user, 100, 'Too expensive');

        expect($history)->toBeNull();
        expect($this->user->fresh()->total_points)->toBe(50);
    });

    it('awards bonus points', function () {
        $history = $this->service->awardBonus($this->user, 25, 'Special event');

        expect($history->points)->toBe(25);
        expect($history->source)->toBe('bonus');
    });
});

describe('Activity Streaks', function () {
    it('tracks first activity', function () {
        $this->service->trackActivity($this->user);

        expect($this->user->fresh()->current_streak)->toBe(1);
        expect($this->user->fresh()->last_activity_date)->toBe(now()->toDateString());
    });

    it('increments streak on consecutive days', function () {
        $this->user->update([
            'current_streak' => 5,
            'last_activity_date' => now()->subDay()->toDateString(),
        ]);

        $this->service->trackActivity($this->user);

        expect($this->user->fresh()->current_streak)->toBe(6);
    });

    it('resets streak when broken', function () {
        $this->user->update([
            'current_streak' => 5,
            'last_activity_date' => now()->subDays(3)->toDateString(),
        ]);

        $this->service->trackActivity($this->user);

        expect($this->user->fresh()->current_streak)->toBe(1);
    });

    it('does not duplicate activity for same day', function () {
        $this->user->update([
            'current_streak' => 3,
            'last_activity_date' => now()->toDateString(),
        ]);

        $this->service->trackActivity($this->user);

        expect($this->user->fresh()->current_streak)->toBe(3);
    });

    it('awards bonus for 7-day streak', function () {
        $this->user->update([
            'current_streak' => 6,
            'last_activity_date' => now()->subDay()->toDateString(),
        ]);

        $this->service->trackActivity($this->user);

        $bonusPoints = PointsHistory::where('user_id', $this->user->id)
            ->where('source', 'bonus')
            ->first();

        expect($bonusPoints)->not->toBeNull();
        expect($bonusPoints->points)->toBe(50);
    });
});

describe('Achievements', function () {
    beforeEach(function () {
        $this->service->seedAchievements();
    });

    it('seeds default achievements', function () {
        expect(Achievement::count())->toBeGreaterThanOrEqual(10);
    });

    it('checks and unlocks booking count achievements', function () {
        $teacherUser = User::factory()->create();
        $teacher = TeacherProfile::factory()->create(['user_id' => $teacherUser->id]);

        // Create 10 completed bookings with unique time slots (after 9 AM to avoid early-bird)
        foreach (range(1, 10) as $i) {
            $startTime = now()->addDays($i)->setHour(10)->setMinute(0);
            $timeSlot = \App\Models\TimeSlot::factory()->create([
                'teacher_id' => $teacher->id,
                'start_at' => $startTime,
                'end_at' => $startTime->copy()->addHour(),
            ]);
            Booking::factory()->create([
                'student_id' => $this->user->id,
                'teacher_id' => $teacher->id,
                'subject_id' => Subject::factory()->create()->id,
                'time_slot_id' => $timeSlot->id,
                'start_at' => $startTime,
                'end_at' => $startTime->copy()->addHour(),
                'status' => 'completed',
            ]);
        }

        $unlocked = $this->service->checkAchievements($this->user);

        // Should unlock first-steps (1 booking) and dedicated-student (10 bookings)
        expect($unlocked)->toHaveCount(2);
        expect(collect($unlocked)->pluck('slug')->toArray())->toContain('first-steps', 'dedicated-student');
        expect($this->user->fresh()->total_points)->toBeGreaterThan(0);
    });

    it('tracks achievement progress', function () {
        $teacherUser = User::factory()->create();
        $teacher = TeacherProfile::factory()->create(['user_id' => $teacherUser->id]);

        // Create 5 completed bookings with unique time slots (after 9 AM to avoid early-bird)
        foreach (range(1, 5) as $i) {
            $startTime = now()->addDays($i)->setHour(10)->setMinute(0);
            $timeSlot = \App\Models\TimeSlot::factory()->create([
                'teacher_id' => $teacher->id,
                'start_at' => $startTime,
                'end_at' => $startTime->copy()->addHour(),
            ]);
            Booking::factory()->create([
                'student_id' => $this->user->id,
                'teacher_id' => $teacher->id,
                'subject_id' => Subject::factory()->create()->id,
                'time_slot_id' => $timeSlot->id,
                'start_at' => $startTime,
                'end_at' => $startTime->copy()->addHour(),
                'status' => 'completed',
            ]);
        }

        $this->service->checkAchievements($this->user);
        $achievements = $this->service->getUserAchievements($this->user);

        $firstSteps = collect($achievements)->first(fn ($a) => $a['achievement']->slug === 'first-steps');
        expect($firstSteps['is_unlocked'])->toBeTrue();

        $dedicated = collect($achievements)->first(fn ($a) => $a['achievement']->slug === 'dedicated-student');
        expect($dedicated['progress'])->toBe(5);
        expect($dedicated['is_unlocked'])->toBeFalse();
    });

    it('awards points when unlocking achievements', function () {
        $teacherUser = User::factory()->create();
        $teacher = TeacherProfile::factory()->create(['user_id' => $teacherUser->id]);

        Booking::factory()->create([
            'student_id' => $this->user->id,
            'teacher_id' => $teacher->id,
            'subject_id' => Subject::factory()->create()->id,
            'time_slot_id' => \App\Models\TimeSlot::factory()->create(['teacher_id' => $teacher->id])->id,
            'status' => 'completed',
        ]);

        $this->service->checkAchievements($this->user);

        expect($this->user->fresh()->total_points)->toBeGreaterThan(0);
    });
});

describe('Badges', function () {
    beforeEach(function () {
        $this->service->seedBadges();
    });

    it('seeds default badges', function () {
        expect(Badge::count())->toBe(4);
    });

    it('awards bronze badge when user reaches 100 points', function () {
        // Award points and check badges
        $this->user->update(['total_points' => 100]);
        $this->service->checkBadgeAwards($this->user);

        $badges = $this->service->getUserBadges($this->user);

        expect($badges)->toHaveCount(1);
        expect($badges[0]['tier'])->toBe('bronze');
    });

    it('awards multiple badges for high points', function () {
        // Set points and check badges
        $this->user->update(['total_points' => 2000]);
        $this->service->checkBadgeAwards($this->user);

        $badges = $this->service->getUserBadges($this->user);

        expect($badges)->toHaveCount(3); // bronze, silver, gold
    });

    it('prevents duplicate badge awards', function () {
        $this->user->update(['total_points' => 100]);
        $this->service->checkBadgeAwards($this->user);
        $this->service->checkBadgeAwards($this->user);

        expect(UserBadge::where('user_id', $this->user->id)->count())->toBe(1);
    });
});

describe('Leaderboard', function () {
    it('updates leaderboard when awarding points', function () {
        $this->service->awardPoints($this->user, 100, 'test', 'Test points');

        $entry = LeaderboardEntry::where('user_id', $this->user->id)
            ->currentMonth()
            ->first();

        expect($entry)->not->toBeNull();
        expect($entry->points)->toBe(100);
    });

    it('accumulates points in leaderboard', function () {
        $this->service->awardPoints($this->user, 100, 'test', 'First');
        $this->service->awardPoints($this->user, 50, 'test', 'Second');

        $entry = LeaderboardEntry::where('user_id', $this->user->id)
            ->currentMonth()
            ->first();

        expect($entry->points)->toBe(150);
    });

    it('returns top users on leaderboard', function () {
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $this->service->awardPoints($this->user, 100, 'test', 'Test');
        $this->service->awardPoints($user2, 200, 'test', 'Test');
        $this->service->awardPoints($user3, 50, 'test', 'Test');

        $leaderboard = $this->service->getLeaderboard(10);

        expect($leaderboard)->toHaveCount(3);
        expect($leaderboard[0]['id'])->toBe($user2->id); // Highest points
        expect($leaderboard[0]['points'])->toBe(200);
    });

    it('returns user rank', function () {
        $user2 = User::factory()->create();

        $this->service->awardPoints($this->user, 100, 'test', 'Test');
        $this->service->awardPoints($user2, 200, 'test', 'Test');

        $rank = $this->service->getUserRank($this->user);

        expect($rank['rank'])->toBe(2);
        expect($rank['points'])->toBe(100);
    });
});

describe('User Levels', function () {
    it('calculates beginner level for new user', function () {
        $level = $this->service->getUserLevel($this->user);

        expect($level['current']['level'])->toBe(1);
        expect($level['current']['name'])->toBe('Beginner');
    });

    it('calculates intermediate level at 300 points', function () {
        $this->user->update(['total_points' => 300]);
        $this->user->refresh();

        $level = $this->service->getUserLevel($this->user);

        expect($level['current']['level'])->toBe(3);
        expect($level['current']['name'])->toBe('Intermediate');
    });

    it('calculates progress to next level', function () {
        $this->user->update(['total_points' => 150]);
        $this->user->refresh();

        $level = $this->service->getUserLevel($this->user);

        expect($level['next']['name'])->toBe('Intermediate');
        expect($level['points_to_next'])->toBe(150); // 300 - 150
        expect($level['progress_percentage'])->toBe(25); // (150-100) / (300-100) * 100
    });

    it('returns null next level for legend', function () {
        $this->user->update(['total_points' => 5000]);
        $this->user->refresh();

        $level = $this->service->getUserLevel($this->user);

        expect($level['current']['name'])->toBe('Legend');
        expect($level['next'])->toBeNull();
        expect($level['progress_percentage'])->toBe(0);
    });
});

describe('User Stats', function () {
    it('returns comprehensive user stats', function () {
        $this->service->seedAchievements();
        $this->service->seedBadges();
        $this->service->awardPoints($this->user, 100, 'test', 'Test');

        $stats = $this->service->getUserStats($this->user);

        expect($stats)->toHaveKeys([
            'total_points',
            'current_streak',
            'level',
            'achievements',
            'badges',
            'leaderboard',
        ]);
        expect($stats['total_points'])->toBe(100);
        expect($stats['achievements']['total'])->toBeGreaterThan(0);
    });
});
