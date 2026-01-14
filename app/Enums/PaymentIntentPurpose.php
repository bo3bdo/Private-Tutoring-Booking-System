<?php

namespace App\Enums;

enum PaymentIntentPurpose: string
{
    case Course = 'course';

    public function label(): string
    {
        return match ($this) {
            self::Course => 'Course Purchase',
        };
    }
}
