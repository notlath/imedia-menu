<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Icons;

use IMedia\Menu\Icons\Providers\DashiconsProvider;
use IMedia\Menu\Icons\Providers\FontAwesomeProvider;
use IMedia\Menu\Icons\Providers\CustomSvgProvider;
use PHPUnit\Framework\TestCase;

final class IconProvidersTest extends TestCase
{
    public function testDashiconsProvider(): void
    {
        $provider = new DashiconsProvider();
        $this->assertSame('dashicons', $provider->id());
        $this->assertNotEmpty($provider->name());

        $icons = $provider->getAvailableIcons();
        $this->assertGreaterThan(50, count($icons));

        $icon = $provider->getIcon('admin-home');
        $this->assertStringContainsString('dashicons-admin-home', $icon);
    }

    public function testDashiconsGetIcon(): void
    {
        $provider = new DashiconsProvider();
        $icon = $provider->getIcon('admin-home');
        $this->assertStringContainsString('dashicons-admin-home', $icon);
        $this->assertStringContainsString('aria-hidden', $icon);
    }

    public function testFontAwesomeProvider(): void
    {
        $provider = new FontAwesomeProvider();
        $this->assertSame('fa', $provider->id());
        $this->assertNotEmpty($provider->name());

        $icons = $provider->getAvailableIcons();
        $this->assertGreaterThan(20, count($icons));

        $icon = $provider->getIcon('home');
        $this->assertStringContainsString('fa-home', $icon);
    }

    public function testFontAwesomeGetIconWithStyle(): void
    {
        $provider = new FontAwesomeProvider();
        $icon = $provider->getIcon('brands fa-facebook');
        $this->assertStringContainsString('brands', $icon);
        $this->assertStringContainsString('fa-fa-facebook', $icon);
    }

    public function testCustomSvgProvider(): void
    {
        $provider = new CustomSvgProvider();
        $this->assertSame('svg', $provider->id());
        $this->assertNotEmpty($provider->name());
    }

    public function testCustomSvgGetIconInvalidId(): void
    {
        $provider = new CustomSvgProvider();
        $this->assertSame('', $provider->getIcon('-1'));
    }

    public function testCustomSvgGetIconZero(): void
    {
        $provider = new CustomSvgProvider();
        $this->assertSame('', $provider->getIcon('0'));
    }
}
