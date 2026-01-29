<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\LiveMeeting;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use App\Models\User;
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

        $bookingId = function (): int {
            $student = User::factory()->create();
            $teacherUser = User::factory()->create();
            $teacher = TeacherProfile::factory()->create(['user_id' => $teacherUser->id]);
            $subject = Subject::factory()->create();
            $timeSlot = TimeSlot::factory()->create([
                'teacher_id' => $teacher->id,
                'start_at' => now()->addDay(),
                'end_at' => now()->addDay()->addHour(),
            ]);

            return Booking::factory()->create([
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'time_slot_id' => $timeSlot->id,
                'start_at' => $timeSlot->start_at,
                'end_at' => $timeSlot->end_at,
            ])->id;
        };

        return [
            'booking_id' => $bookingId,
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
