<?php

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an achievement', function () {
    $achievement = Achievement::factory()->create([
        'name' => 'Test Achievement',
        'slug' => 'test-achievement',
        'points' => 100,
        'threshold' => 5,
    ]);

    expect($achievement->name)->toBe('Test Achievement');
    expect($achievement->points)->toBe(100);
    expect($achievement->threshold)->toBe(5);
});

it('can scope active achievements', function () {
    Achievement::factory()->create(['is_active' => true, 'slug' => 'active-achievement']);
    Achievement::factory()->create(['is_active' => false, 'slug' => 'inactive-achievement']);

    $active = Achievement::active()->get();

    expect($active)->toHaveCount(1);
    expect($active->first()->slug)->toBe('active-achievement');
});

it('can scope by type', function () {
    Achievement::factory()->create(['type' => 'booking_count', 'slug' => 'booking-achievement']);
    Achievement::factory()->create(['type' => 'course_completed', 'slug' => 'course-achievement']);

    $bookings = Achievement::byType('booking_count')->get();

    expect($bookings)->toHaveCount(1);
    expect($bookings->first()->slug)->toBe('booking-achievement');
});

it('has many user achievements', function () {
    $achievement = Achievement::factory()->create();
    $user = User::factory()->create();

    UserAchievement::create([
        'user_id' => $user->id,
        'achievement_id' => $achievement->id,
        'progress' => 3,
    ]);

    expect($achievement->userAchievements)->toHaveCount(1);
    expect($achievement->userAchievements->first()->user_id)->toBe($user->id);
});

describe('User Achievement', function () {
    it('belongs to user and achievement', function () {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $userAchievement = UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'progress' => 0,
        ]);

        expect($userAchievement->user->id)->toBe($user->id);
        expect($userAchievement->achievement->id)->toBe($achievement->id);
    });

    it('checks if unlocked', function () {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $userAchievement = UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'unlocked_at' => null,
        ]);

        expect($userAchievement->isUnlocked())->toBeFalse();

        $userAchievement->update(['unlocked_at' => now()]);

        expect($userAchievement->fresh()->isUnlocked())->toBeTrue();
    });

    it('unlocks achievement', function () {
        $achievement = Achievement::factory()->create(['threshold' => 5]);
        $user = User::factory()->create();

        $userAchievement = UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'progress' => 3,
            'unlocked_at' => null,
        ]);

        $userAchievement->unlock();

        expect($userAchievement->fresh()->isUnlocked())->toBeTrue();
        expect($userAchievement->fresh()->progress)->toBe(5);
    });
});
