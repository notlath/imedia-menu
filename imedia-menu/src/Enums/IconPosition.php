<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum IconPosition: string
{
    case Before = 'before';
    case After  = 'after';

    public function label(): string
    {
        return match ($this) {
            self::Before => __('Before Text', 'imedia-menu'),
            self::After  => __('After Text', 'imedia-menu'),
        };
    }
}
