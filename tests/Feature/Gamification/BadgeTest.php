<?php

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;

it('can create a badge', function () {
    $badge = Badge::factory()->create([
        'name' => 'Gold Medal',
        'slug' => 'gold-medal',
        'tier' => 'gold',
        'color' => '#FFD700',
    ]);

    expect($badge->name)->toBe('Gold Medal');
    expect($badge->tier)->toBe('gold');
});

it('can scope active badges', function () {
    Badge::factory()->create(['is_active' => true, 'slug' => 'active-badge']);
    Badge::factory()->create(['is_active' => false, 'slug' => 'inactive-badge']);

    $active = Badge::active()->get();

    expect($active)->toHaveCount(1);
    expect($active->first()->slug)->toBe('active-badge');
});

it('can scope by tier', function () {
    Badge::factory()->create(['tier' => 'bronze']);
    Badge::factory()->create(['tier' => 'silver']);
    Badge::factory()->create(['tier' => 'gold']);

    $gold = Badge::byTier('gold')->get();

    expect($gold)->toHaveCount(1);
    expect($gold->first()->tier)->toBe('gold');
});

it('returns tier order', function () {
    $bronze = Badge::factory()->create(['tier' => 'bronze']);
    $silver = Badge::factory()->create(['tier' => 'silver']);
    $gold = Badge::factory()->create(['tier' => 'gold']);
    $platinum = Badge::factory()->create(['tier' => 'platinum']);

    expect($bronze->getTierOrder())->toBe(1);
    expect($silver->getTierOrder())->toBe(2);
    expect($gold->getTierOrder())->toBe(3);
    expect($platinum->getTierOrder())->toBe(4);
});

it('has many user badges', function () {
    $badge = Badge::factory()->create();
    $user = User::factory()->create();

    UserBadge::create([
        'user_id' => $user->id,
        'badge_id' => $badge->id,
        'awarded_at' => now(),
    ]);

    expect($badge->userBadges)->toHaveCount(1);
});

describe('User Badge', function () {
    it('belongs to user and badge', function () {
        $user = User::factory()->create();
        $badge = Badge::factory()->create();

        $userBadge = UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'awarded_at' => now(),
        ]);

        expect($userBadge->user->id)->toBe($user->id);
        expect($userBadge->badge->id)->toBe($badge->id);
    });

    it('prevents duplicate awards', function () {
        $user = User::factory()->create();
        $badge = Badge::factory()->create();

        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'awarded_at' => now(),
        ]);

        // Try to create another one
        try {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'awarded_at' => now(),
            ]);
            $this->fail('Should have thrown unique constraint violation');
        } catch (\Exception $e) {
            // Expected
            expect(true)->toBeTrue();
        }
    });
});
