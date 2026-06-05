<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum LayoutDirection: string
{
    case LTR = 'ltr';
    case RTL = 'rtl';

    public function isRTL(): bool
    {
        return $this === self::RTL;
    }
}
