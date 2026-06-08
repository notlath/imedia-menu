<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\PanelWidth;
use IMedia\Menu\Enums\AnimationType;
use IMedia\Menu\Enums\DeviceType;
use IMedia\Menu\Enums\IconPosition;
use IMedia\Menu\Enums\BadgePosition;
use IMedia\Menu\Enums\LayoutDirection;
use PHPUnit\Framework\TestCase;

final class RemainingEnumTest extends TestCase
{
    public function testPanelWidth(): void
    {
        $values = array_map(fn (PanelWidth $t) => $t->value, PanelWidth::cases());
        $this->assertContains('full', $values);
        $this->assertContains('container', $values);
        $this->assertContains('custom', $values);
        foreach (PanelWidth::cases() as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function testAnimationType(): void
    {
        $values = array_map(fn (AnimationType $t) => $t->value, AnimationType::cases());
        $this->assertContains('none', $values);
        $this->assertContains('fade', $values);
        $this->assertContains('slide', $values);
        foreach (AnimationType::cases() as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function testDeviceType(): void
    {
        $this->assertTrue(DeviceType::tryFrom('desktop') !== null);
        $this->assertTrue(DeviceType::tryFrom('mobile') !== null);
    }

    public function testIconPosition(): void
    {
        $values = array_map(fn (IconPosition $t) => $t->value, IconPosition::cases());
        $this->assertContains('before', $values);
        $this->assertContains('after', $values);
        foreach (IconPosition::cases() as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function testBadgePosition(): void
    {
        $values = array_map(fn (BadgePosition $t) => $t->value, BadgePosition::cases());
        $this->assertContains('top-right', $values);
        $this->assertContains('top-left', $values);
        $this->assertContains('inline', $values);
        foreach (BadgePosition::cases() as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function testLayoutDirection(): void
    {
        $values = array_map(fn (LayoutDirection $t) => $t->value, LayoutDirection::cases());
        $this->assertContains('ltr', $values);
        $this->assertContains('rtl', $values);
    }
}
