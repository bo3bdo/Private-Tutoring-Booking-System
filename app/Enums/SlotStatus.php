<?php

namespace App\Enums;

enum SlotStatus: string
{
    case Available = 'available';
    case Blocked = 'blocked';
    case Booked = 'booked';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::Blocked => 'Blocked',
            self::Booked => 'Booked',
        };
    }
}
