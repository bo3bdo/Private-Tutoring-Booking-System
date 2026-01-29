<?php

namespace Database\Factories;

use App\Models\AiRecommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiRecommendationFactory extends Factory
{
    protected $model = AiRecommendation::class;

    public function definition(): array
    {
        $types = ['teacher', 'course', 'time_slot', 'subject'];
        $type = $this->faker->randomElement($types);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'recommendation_data' => [
                ['id' => 1, 'score' => $this->faker->numberBetween(70, 100)],
                ['id' => 2, 'score' => $this->faker->numberBetween(60, 95)],
                ['id' => 3, 'score' => $this->faker->numberBetween(50, 90)],
            ],
            'context' => [
                'reason' => $this->faker->sentence,
                'algorithm_version' => 'v1.0',
            ],
            'algorithm_version' => 'v1.0',
            'generated_at' => now()->subHours($this->faker->numberBetween(1, 24)),
        ];
    }
}
