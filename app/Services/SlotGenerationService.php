<?php

namespace App\Services;

use App\Enums\SlotStatus;
use App\Models\TeacherProfile;
use App\Models\TimeSlot;
use Carbon\Carbon;

class SlotGenerationService
{
    public function generateSlots(
        TeacherProfile $teacher,
        Carbon $startDate,
        Carbon $endDate,
        int $durationMinutes,
        ?int $subjectId = null
    ): int {
        $generated = 0;
        $availabilities = $teacher->availabilities()->where('is_active', true)->get();

        if ($availabilities->isEmpty()) {
            return 0;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $weekday = $currentDate->dayOfWeek;

            foreach ($availabilities as $availability) {
                if ($availability->weekday == $weekday) {
                    $slots = $this->generateSlotsForDay(
                        $teacher,
                        $currentDate,
                        $availability->start_time,
                        $availability->end_time,
                        $durationMinutes,
                        $subjectId
                    );

                    $generated += $slots;
                }
            }

            $currentDate->addDay();
        }

        return $generated;
    }

    protected function generateSlotsForDay(
        TeacherProfile $teacher,
        Carbon $date,
        string $startTime,
        string $endTime,
        int $durationMinutes,
        ?int $subjectId = null
    ): int {
        $generated = 0;

        // Parse time strings (format: H:i or H:i:s) and set them on the date
        $startTimeParts = explode(':', $startTime);
        $endTimeParts = explode(':', $endTime);

        $start = $date->copy()->setTime(
            (int) ($startTimeParts[0] ?? 0),
            (int) ($startTimeParts[1] ?? 0),
            (int) ($startTimeParts[2] ?? 0)
        );

        $end = $date->copy()->setTime(
            (int) ($endTimeParts[0] ?? 0),
            (int) ($endTimeParts[1] ?? 0),
            (int) ($endTimeParts[2] ?? 0)
        );

        $current = $start->copy();

        while ($current->copy()->addMinutes($durationMinutes)->lte($end)) {
            $slotEnd = $current->copy()->addMinutes($durationMinutes);

            if ($slotEnd->isPast()) {
                $current->addMinutes($durationMinutes);

                continue;
            }

            $exists = TimeSlot::where('teacher_id', $teacher->id)
                ->where('start_at', $current)
                ->where('end_at', $slotEnd)
                ->exists();

            if (! $exists) {
                TimeSlot::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'start_at' => $current,
                    'end_at' => $slotEnd,
                    'status' => SlotStatus::Available,
                    'created_by' => auth()->id(),
                ]);

                $generated++;
            }

            $current->addMinutes($durationMinutes);
        }

        return $generated;
    }
}
