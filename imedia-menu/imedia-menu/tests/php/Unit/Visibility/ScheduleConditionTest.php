<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\ScheduleCondition;
use PHPUnit\Framework\TestCase;

final class ScheduleConditionTest extends TestCase
{
    private ScheduleCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new ScheduleCondition();
    }

    public function testType(): void
    {
        $this->assertSame('schedule', $this->condition->type());
    }

    public function testNoDatesReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([]));
    }

    public function testPastStartDateReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([
            'start' => '2020-01-01T00:00:00',
        ]));
    }

    public function testFutureStartDateReturnsFalse(): void
    {
        $result = $this->condition->evaluate([
            'start' => '2099-01-01T00:00:00',
        ]);
        $this->assertFalse($result);
    }

    public function testPastEndDateReturnsFalse(): void
    {
        $result = $this->condition->evaluate([
            'end' => '2020-01-01T00:00:00',
        ]);
        $this->assertFalse($result);
    }

    public function testFutureEndDateReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([
            'end' => '2099-01-01T00:00:00',
        ]));
    }

    public function testTimestampBasedDates(): void
    {
        $future = time() + 86400 * 365;
        $past   = time() - 86400 * 365;

        $this->assertTrue($this->condition->evaluate([
            'start' => $past,
            'end'   => $future,
        ]));
    }
}
