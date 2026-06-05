<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\DeviceTypeCondition;
use PHPUnit\Framework\TestCase;

final class DeviceTypeConditionTest extends TestCase
{
    private DeviceTypeCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new DeviceTypeCondition();
    }

    public function testType(): void
    {
        $this->assertSame('device_type', $this->condition->type());
    }

    public function testReturnsTrueWhenNoDevicesConfigured(): void
    {
        $this->assertTrue($this->condition->evaluate(['devices' => []]));
    }

    public function testReturnsTrueForDesktopOnlyOnDesktop(): void
    {
        $this->assertTrue($this->condition->evaluate(['devices' => ['desktop']]));
    }

    public function testReturnsFalseForMobileOnlyOnDesktop(): void
    {
        $this->assertFalse($this->condition->evaluate(['devices' => ['mobile']]));
    }
}
