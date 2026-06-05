<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class ScheduleCondition implements VisibilityCondition
{
    public function type(): string
    {
        return 'schedule';
    }

    public function label(): string
    {
        return __('Date/Time Scheduling', 'imedia-menu');
    }

    public function evaluate(array $config): bool
    {
        $start = $config['start'] ?? null;
        $end   = $config['end'] ?? null;
        $now   = current_time('timestamp');

        if ($start) {
            $startTime = is_numeric($start) ? (int) $start : strtotime((string) $start);
            if ($startTime !== false && $now < $startTime) {
                return false;
            }
        }

        if ($end) {
            $endTime = is_numeric($end) ? (int) $end : strtotime((string) $end);
            if ($endTime !== false && $now > $endTime) {
                return false;
            }
        }

        return true;
    }
}
