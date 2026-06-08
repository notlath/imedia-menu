<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Icons;

use IMedia\Menu\Icons\IconManager;
use IMedia\Menu\Icons\Providers\DashiconsProvider;
use IMedia\Menu\Icons\Providers\FontAwesomeProvider;
use IMedia\Menu\Icons\Providers\CustomSvgProvider;
use PHPUnit\Framework\TestCase;

final class IconManagerTest extends TestCase
{
    private IconManager $manager;

    protected function setUp(): void
    {
        $this->manager = new IconManager();
    }

    public function testRegistryReturnsProviders(): void
    {
        $this->manager->register(new DashiconsProvider());
        $this->manager->register(new FontAwesomeProvider());
        $this->manager->register(new CustomSvgProvider());

        $registry = $this->manager->getRegistry();
        $this->assertCount(3, $registry->getAll());
    }

    public function testGetIconReturnsEmptyForMissingProvider(): void
    {
        $this->assertSame('', $this->manager->getIcon('unknown:admin-home'));
    }

    public function testGetIconWithDashiconsProvider(): void
    {
        $this->manager->register(new DashiconsProvider());
        $icon = $this->manager->getIcon('dashicons:admin-home');
        $this->assertStringContainsString('dashicons-admin-home', $icon);
    }

    public function testGetAvailableIcons(): void
    {
        $this->manager->register(new DashiconsProvider());
        $icons = $this->manager->getAvailableIcons();

        $this->assertArrayHasKey('dashicons', $icons);
        $this->assertArrayHasKey('name', $icons['dashicons']);
        $this->assertArrayHasKey('icons', $icons['dashicons']);
    }
}
