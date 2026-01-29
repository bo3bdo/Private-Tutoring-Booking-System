<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\LiveMeeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveMeetingFactory extends Factory
{
    protected $model = LiveMeeting::class;

    public function definition(): array
    {
        $providers = ['zoom', 'google_meet', 'microsoft_teams'];
        $provider = $this->faker->randomElement($providers);

        $meetingCode = match ($provider) {
            'zoom' => $this->faker->numerify('##########'),
            'google_meet' => $this->faker->regexify('[a-z]{3}-[a-z]{4}-[a-z]{3}'),
            default => $this->faker->uuid,
        };

        return [
            'booking_id' => Booking::factory(),
            'provider' => $provider,
            'meeting_id' => $meetingCode,
            'meeting_url' => "https://{$provider}.com/{$meetingCode}",
            'join_url' => "https://{$provider}.com/join/{$meetingCode}",
            'host_url' => "https://{$provider}.com/start/{$meetingCode}",
            'password' => $this->faker->optional()->password,
            'scheduled_at' => now()->addDays($this->faker->numberBetween(1, 7)),
            'duration_minutes' => $this->faker->numberBetween(30, 120),
            'metadata' => [],
            'started_at' => null,
            'ended_at' => null,
            'recording_url' => null,
        ];
    }

    public function started(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => now(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'started_at' => now()->subHours(1),
            'ended_at' => now(),
        ]);
    }
}
