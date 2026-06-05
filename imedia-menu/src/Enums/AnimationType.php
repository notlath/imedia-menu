<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum AnimationType: string
{
    case Fade     = 'fade';
    case Slide    = 'slide';
    case None     = 'none';

    public function label(): string
    {
        return match ($this) {
            self::Fade  => __('Fade', 'imedia-menu'),
            self::Slide => __('Slide Down', 'imedia-menu'),
            self::None  => __('None', 'imedia-menu'),
        };
    }
}
