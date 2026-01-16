<?php

namespace App\Enums;

enum LessonMode: string
{
    case Online = 'online';
    case InPerson = 'in_person';

    public function label(): string
    {
        return match ($this) {
            self::Online => __('common.Online'),
            self::InPerson => __('common.In Person'),
        };
    }
}
