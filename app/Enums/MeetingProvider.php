<?php

namespace App\Enums;

enum MeetingProvider: string
{
    case None = 'none';
    case Custom = 'custom';
    case Zoom = 'zoom';
    case GoogleMeet = 'google_meet';

    public function label(): string
    {
        return match ($this) {
            self::None => 'None',
            self::Custom => 'Custom',
            self::Zoom => 'Zoom',
            self::GoogleMeet => 'Google Meet',
        };
    }
}
