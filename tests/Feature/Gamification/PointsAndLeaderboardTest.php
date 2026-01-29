<?php

use App\Models\LeaderboardEntry;
use App\Models\PointsHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create points history', function () {
    $user = User::factory()->create();

    $history = PointsHistory::create([
        'user_id' => $user->id,
        'points' => 100,
        'type' => 'earned',
        'source' => 'booking',
        'description' => 'Completed a booking',
    ]);

    expect($history->points)->toBe(100);
    expect($history->type)->toBe('earned');
    expect($history->source)->toBe('booking');
});

it('scopes earned points', function () {
    $user = User::factory()->create();

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => 100,
        'type' => 'earned',
        'source' => 'booking',
        'description' => 'Test',
    ]);

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => -50,
        'type' => 'spent',
        'source' => 'reward',
        'description' => 'Test',
    ]);

    $earned = PointsHistory::where('user_id', $user->id)->earned()->get();

    expect($earned)->toHaveCount(1);
    expect($earned->first()->points)->toBe(100);
});

it('scopes spent points', function () {
    $user = User::factory()->create();

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => 100,
        'type' => 'earned',
        'source' => 'booking',
        'description' => 'Test',
    ]);

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => -50,
        'type' => 'spent',
        'source' => 'reward',
        'description' => 'Test',
    ]);

    $spent = PointsHistory::where('user_id', $user->id)->spent()->get();

    expect($spent)->toHaveCount(1);
    expect($spent->first()->points)->toBe(-50);
});

it('scopes by source', function () {
    $user = User::factory()->create();

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => 100,
        'type' => 'earned',
        'source' => 'booking',
        'description' => 'Test',
    ]);

    PointsHistory::create([
        'user_id' => $user->id,
        'points' => 50,
        'type' => 'earned',
        'source' => 'review',
        'description' => 'Test',
    ]);

    $bookings = PointsHistory::where('user_id', $user->id)->bySource('booking')->get();

    expect($bookings)->toHaveCount(1);
    expect($bookings->first()->source)->toBe('booking');
});

it('belongs to user', function () {
    $user = User::factory()->create();

    $history = PointsHistory::create([
        'user_id' => $user->id,
        'points' => 100,
        'type' => 'earned',
        'source' => 'booking',
        'description' => 'Test',
    ]);

    expect($history->user->id)->toBe($user->id);
});

describe('Leaderboard Entry', function () {
    it('can create leaderboard entry', function () {
        $user = User::factory()->create();

        $entry = LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => 2026,
            'month' => 1,
            'points' => 500,
            'bookings_count' => 10,
            'courses_completed' => 2,
            'rank' => 1,
        ]);

        expect($entry->points)->toBe(500);
        expect($entry->year)->toBe(2026);
        expect($entry->month)->toBe(1);
    });

    it('belongs to user', function () {
        $user = User::factory()->create();

        $entry = LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => 2026,
            'month' => 1,
            'points' => 100,
        ]);

        expect($entry->user->id)->toBe($user->id);
    });

    it('scopes by period', function () {
        $user = User::factory()->create();

        LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => 2026,
            'month' => 1,
            'points' => 100,
        ]);

        LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => 2026,
            'month' => 2,
            'points' => 200,
        ]);

        $january = LeaderboardEntry::forPeriod(2026, 1)->get();

        expect($january)->toHaveCount(1);
        expect($january->first()->points)->toBe(100);
    });

    it('scopes current month', function () {
        $user = User::factory()->create();

        LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => now()->year,
            'month' => now()->month,
            'points' => 100,
        ]);

        LeaderboardEntry::create([
            'user_id' => $user->id,
            'year' => now()->subMonth()->year,
            'month' => now()->subMonth()->month,
            'points' => 200,
        ]);

        $current = LeaderboardEntry::currentMonth()->get();

        expect($current)->toHaveCount(1);
        expect($current->first()->points)->toBe(100);
    });

    it('scopes top entries', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        LeaderboardEntry::create([
            'user_id' => $user1->id,
            'year' => now()->year,
            'month' => now()->month,
            'points' => 300,
        ]);

        LeaderboardEntry::create([
            'user_id' => $user2->id,
            'year' => now()->year,
            'month' => now()->month,
            'points' => 500,
        ]);

        LeaderboardEntry::create([
            'user_id' => $user3->id,
            'year' => now()->year,
            'month' => now()->month,
            'points' => 100,
        ]);

        $top = LeaderboardEntry::currentMonth()->top(2)->get();

        expect($top)->toHaveCount(2);
        expect($top->first()->points)->toBe(500);
        expect($top->last()->points)->toBe(300);
    });
});
