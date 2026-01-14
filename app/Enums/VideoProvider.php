<?php

namespace App\Enums;

enum VideoProvider: string
{
    case Url = 'url';
    case Youtube = 'youtube';
    case Vimeo = 'vimeo';
    case S3 = 's3';
    case Cloudflare = 'cloudflare';

    public function label(): string
    {
        return match ($this) {
            self::Url => 'Direct URL',
            self::Youtube => 'YouTube',
            self::Vimeo => 'Vimeo',
            self::S3 => 'AWS S3',
            self::Cloudflare => 'Cloudflare Stream',
        };
    }
}
