<?php

namespace Database\Factories;

use App\Enums\SlotStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => SlotStatus::Available,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDay()->addHour(),
        ];
    }
}
