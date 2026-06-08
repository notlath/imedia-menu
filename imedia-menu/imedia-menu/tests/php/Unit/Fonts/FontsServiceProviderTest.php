<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Fonts;

use IMedia\Menu\Fonts\FontsManager;
use IMedia\Menu\Providers\FontsServiceProvider;
use PHPUnit\Framework\TestCase;

final class FontsServiceProviderTest extends TestCase {

	public function testRegisterCreatesManager(): void {
		$provider = new FontsServiceProvider();
		$provider->register();

		$this->assertInstanceOf( FontsManager::class, $provider->getManager() );
	}

	public function testBootHooksEnqueue(): void {
		$GLOBALS['__wp_actions'] = array();

		$provider = new FontsServiceProvider();
		$provider->register();
		$provider->boot();

		$hooks = $GLOBALS['__wp_actions']['wp_enqueue_scripts'] ?? array();
		$this->assertContains( array( $provider->getManager(), 'enqueue' ), $hooks );

		unset( $GLOBALS['__wp_actions'] );
	}
}
