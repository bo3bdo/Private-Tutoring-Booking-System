<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence,
            'icon' => $this->faker->randomElement(['medal', 'award', 'star', 'trophy', 'crown']),
            'color' => $this->faker->hexColor,
            'tier' => $this->faker->randomElement(['bronze', 'silver', 'gold', 'platinum']),
            'is_active' => true,
        ];
    }
}
