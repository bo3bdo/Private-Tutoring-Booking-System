<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        $types = ['booking_count', 'course_completed', 'review_given', 'streak', 'perfect_attendance', 'early_booking'];
        $type = $this->faker->randomElement($types);

        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence,
            'icon' => $this->faker->randomElement(['award', 'star', 'trophy', 'medal', 'check-circle']),
            'color' => $this->faker->hexColor,
            'points' => $this->faker->numberBetween(50, 1000),
            'type' => $type,
            'threshold' => $this->faker->numberBetween(1, 50),
            'is_active' => true,
        ];
    }
}
