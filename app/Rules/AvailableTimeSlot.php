<?php

namespace App\Rules;

use App\Enums\SlotStatus;
use App\Models\TimeSlot;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableTimeSlot implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slot = TimeSlot::find($value);

        if (! $slot) {
            $fail('The selected time slot does not exist.');

            return;
        }

        if ($slot->status !== SlotStatus::Available) {
            $fail('The selected time slot is no longer available.');

            return;
        }

        if ($slot->start_at < now()) {
            $fail('Cannot book a time slot in the past.');

            return;
        }

        // Check if slot is blocked
        if ($slot->is_blocked) {
            $fail('This time slot has been blocked by the teacher.');

            return;
        }

        // Check if slot is too close to current time (e.g., less than 1 hour)
        if ($slot->start_at < now()->addHour()) {
            $fail('Bookings must be made at least 1 hour in advance.');

            return;
        }
    }
}
