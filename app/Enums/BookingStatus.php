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
            self::AwaitingPayment => __('common.Awaiting Payment'),
            self::Confirmed => __('common.Confirmed'),
            self::Cancelled => __('common.Cancelled'),
            self::Completed => __('common.Completed'),
            self::NoShow => __('common.No Show'),
            self::Rescheduled => __('common.Rescheduled'),
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
