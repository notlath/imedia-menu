<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Cache;

use IMedia\Menu\Cache\MenuCache;
use PHPUnit\Framework\TestCase;

final class MenuCacheFlushTest extends TestCase
{
    public function testFlushDeletesTransientsAndObjectCacheGroup(): void
    {
        $GLOBALS['__wp_cache_flush_group_calls'] = array();

        $cache = new MenuCache();
        $cache->flush();

        $this->assertContains(
            'imedia_menu',
            $GLOBALS['__wp_cache_flush_group_calls'] ?? array(),
            'MenuCache::flush() must call wp_cache_flush_group("imedia_menu") to clear stale HTML on hosts with persistent object cache.'
        );
    }
}
