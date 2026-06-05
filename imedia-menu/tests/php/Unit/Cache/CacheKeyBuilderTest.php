<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Cache;

use IMedia\Menu\Cache\CacheKeyBuilder;
use PHPUnit\Framework\TestCase;

final class CacheKeyBuilderTest extends TestCase
{
    private CacheKeyBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new CacheKeyBuilder();
    }

    public function testBuildMenuKey(): void
    {
        $key = $this->builder->build(5);
        $this->assertStringContainsString('5', $key);
        $this->assertStringStartsWith('imedia_menu_', $key);
    }

    public function testCacheKeyStability(): void
    {
        $key1 = $this->builder->build(1);
        $key2 = $this->builder->build(1);

        $this->assertSame($key1, $key2);
    }

    public function testDifferentMenuIdsProduceDifferentKeys(): void
    {
        $key1 = $this->builder->build(1);
        $key2 = $this->builder->build(2);

        $this->assertNotSame($key1, $key2);
    }

    public function testBuildPanelKey(): void
    {
        $key = $this->builder->buildPanelKey(10, 5);
        $this->assertStringContainsString('10', $key);
        $this->assertStringContainsString('panel', $key);
    }

    public function testBuildPanelKeyDifferentMenuItems(): void
    {
        $key1 = $this->builder->buildPanelKey(10, 5);
        $key2 = $this->builder->buildPanelKey(11, 5);

        $this->assertNotSame($key1, $key2);
    }
}
