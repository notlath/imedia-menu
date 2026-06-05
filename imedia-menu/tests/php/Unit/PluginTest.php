<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit;

use IMedia\Menu\Plugin;
use PHPUnit\Framework\TestCase;

final class PluginTest extends TestCase
{
    public function testInitReturnsInstance(): void
    {
        Plugin::init();
        $instance = Plugin::instance();

        $this->assertInstanceOf(Plugin::class, $instance);
    }

    public function testInstanceReturnsSingleton(): void
    {
        $this->assertSame(Plugin::instance(), Plugin::instance());
    }
}
