<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Cache;

use IMedia\Menu\Cache\MenuCache;
use PHPUnit\Framework\TestCase;

final class MenuCacheTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_post_meta'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_post_meta'] );
		parent::tearDown();
	}

	public function testGetMenuHtmlReturnsNullOnMiss(): void {
		$cache = new MenuCache();
		$result = $cache->getMenuHtml( 5 );
		$this->assertNull( $result );
	}

	public function testGetMenuHtmlSetsCacheKeyReference(): void {
		$cache    = new MenuCache();
		$cacheKey = null;
		$cache->getMenuHtml( 5, $cacheKey );
		$this->assertNotNull( $cacheKey );
	}

	public function testSetMenuHtmlReturnsTrue(): void {
		$cache = new MenuCache();
		$result = $cache->setMenuHtml( 5, '<nav>test</nav>', 3600 );
		$this->assertTrue( $result );
	}

	public function testGetReturnsNullOnMiss(): void {
		$cache = new MenuCache();
		$this->assertNull( $cache->get( 'unknown_key' ) );
	}

	public function testSetReturnsTrue(): void {
		$cache = new MenuCache();
		$this->assertTrue( $cache->set( 'some_key', 'data', 3600 ) );
	}

	public function testDeleteReturnsTrue(): void {
		$cache = new MenuCache();
		$this->assertTrue( $cache->delete( 'some_key' ) );
	}

	public function testFlushReturnsTrue(): void {
		$cache = new MenuCache();
		$this->assertTrue( $cache->flush() );
	}

	public function testMenuCacheImplementsCacheable(): void {
		$cache = new MenuCache();
		$this->assertInstanceOf( \IMedia\Menu\Contracts\Cacheable::class, $cache );
	}
}
