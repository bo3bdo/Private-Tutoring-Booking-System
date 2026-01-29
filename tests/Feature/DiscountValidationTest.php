<?php

use App\Models\Discount;
use App\Models\DiscountUsage;
use App\Models\User;
use App\Services\DiscountService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('student');
    $this->discountService = app(DiscountService::class);
});

it('validates a valid discount code', function () {
    $discount = Discount::factory()->percentage(20)->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeTrue();
    expect($result['discount_amount'])->toBe(20.00);
    expect($result['final_amount'])->toBe(80.00);
});

it('rejects an invalid discount code', function () {
    $result = $this->discountService->validateDiscount('INVALIDCODE', $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('Invalid');
});

it('rejects an expired discount code', function () {
    $discount = Discount::factory()->expired()->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('expired');
});

it('rejects a discount code that is not yet active', function () {
    $discount = Discount::factory()->notYetActive()->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('not yet active');
});

it('rejects an inactive discount code', function () {
    $discount = Discount::factory()->inactive()->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('Invalid');
});

it('rejects a discount code that has reached maximum uses', function () {
    $discount = Discount::factory()->withMaxUses(1)->create();

    // Create a usage record
    DiscountUsage::create([
        'discount_id' => $discount->id,
        'user_id' => User::factory()->create()->id,
        'amount' => 100.00,
        'discount_amount' => 10.00,
        'final_amount' => 90.00,
    ]);

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('maximum uses');
});

it('rejects a discount code when user has reached per-user limit', function () {
    $discount = Discount::factory()->withMaxUsesPerUser(1)->create();

    // Create a usage record for this user
    DiscountUsage::create([
        'discount_id' => $discount->id,
        'user_id' => $this->user->id,
        'amount' => 100.00,
        'discount_amount' => 10.00,
        'final_amount' => 90.00,
    ]);

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('already used');
});

it('rejects a discount code when amount is below minimum', function () {
    $discount = Discount::factory()->withMinAmount(50.00)->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 25.00);

    expect($result['valid'])->toBeFalse();
    expect($result['message'])->toContain('Minimum amount');
});

it('calculates fixed discount correctly', function () {
    $discount = Discount::factory()->fixed(15.00)->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeTrue();
    expect($result['discount_amount'])->toBe(15.00);
    expect($result['final_amount'])->toBe(85.00);
});

it('caps fixed discount at order amount', function () {
    $discount = Discount::factory()->fixed(50.00)->create();

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 30.00);

    expect($result['valid'])->toBeTrue();
    expect($result['discount_amount'])->toBe(30.00);
    expect($result['final_amount'])->toBe(0.00);
});

it('caps percentage discount at max_discount_amount', function () {
    $discount = Discount::factory()->percentage(50)->create([
        'max_discount_amount' => 20.00,
    ]);

    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeTrue();
    expect($result['discount_amount'])->toBe(20.00);
    expect($result['final_amount'])->toBe(80.00);
});

it('allows multiple users to use same discount within limits', function () {
    $discount = Discount::factory()->withMaxUses(3)->create();

    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    // First usage
    DiscountUsage::create([
        'discount_id' => $discount->id,
        'user_id' => $user2->id,
        'amount' => 100.00,
        'discount_amount' => 10.00,
        'final_amount' => 90.00,
    ]);

    // Second usage
    DiscountUsage::create([
        'discount_id' => $discount->id,
        'user_id' => $user3->id,
        'amount' => 100.00,
        'discount_amount' => 10.00,
        'final_amount' => 90.00,
    ]);

    // Third usage should still be valid
    $result = $this->discountService->validateDiscount($discount->code, $this->user, 100.00);

    expect($result['valid'])->toBeTrue();
});
