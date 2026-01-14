<?php

namespace App\Enums;

enum BookingStatus: string
{
    case AwaitingPayment = 'awaiting_payment';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case NoShow = 'no_show';
    case Rescheduled = 'rescheduled';

    public function label(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'Awaiting Payment',
            self::Confirmed => 'Confirmed',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
            self::NoShow => 'No Show',
            self::Rescheduled => 'Rescheduled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'yellow',
            self::Confirmed => 'green',
            self::Cancelled => 'red',
            self::Completed => 'blue',
            self::NoShow => 'rose',
            self::Rescheduled => 'purple',
        };
    }
}
