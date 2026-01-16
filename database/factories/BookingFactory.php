<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\LessonMode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => BookingStatus::Confirmed,
            'lesson_mode' => LessonMode::Online,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDay()->addHour(),
        ];
    }
}
