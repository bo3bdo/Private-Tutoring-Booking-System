<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAchievementFactory extends Factory
{
    protected $model = UserAchievement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'achievement_id' => Achievement::factory(),
            'progress' => $this->faker->numberBetween(0, 10),
            'unlocked_at' => null,
        ];
    }

    public function unlocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress' => 10,
            'unlocked_at' => now(),
        ]);
    }
}
