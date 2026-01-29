<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('DISCOUNT????')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['percentage', 'fixed']),
            'value' => $this->faker->randomFloat(2, 5, 50),
            'max_discount_amount' => null,
            'min_amount' => null,
            'max_uses' => null,
            'max_uses_per_user' => null,
            'starts_at' => null,
            'expires_at' => null,
            'is_active' => true,
        ];
    }

    public function percentage(float $value = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percentage',
            'value' => $value,
        ]);
    }

    public function fixed(float $value = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'fixed',
            'value' => $value,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }

    public function notYetActive(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->addDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withMaxUses(int $maxUses = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'max_uses' => $maxUses,
        ]);
    }

    public function withMaxUsesPerUser(int $maxUsesPerUser = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'max_uses_per_user' => $maxUsesPerUser,
        ]);
    }

    public function withMinAmount(float $minAmount): static
    {
        return $this->state(fn (array $attributes) => [
            'min_amount' => $minAmount,
        ]);
    }
}
