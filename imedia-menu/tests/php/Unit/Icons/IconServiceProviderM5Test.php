<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Icons;

use IMedia\Menu\Providers\IconServiceProvider;
use PHPUnit\Framework\TestCase;

final class IconServiceProviderM5Test extends TestCase {

	protected function setUp(): void {
		$GLOBALS['_imedia_menu_options'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
	}

	/**
	 * Helper to set boot() expectations: boot() in_array checks the *values*
	 * of icon_providers, so pass a flat list of provider ID strings.
	 */
	private function setProviderEnabled( string $id ): void {
		$enabled   = $GLOBALS['_imedia_menu_options']['imedia_menu_settings']['icon_providers'] ?? array();
		$enabled[] = $id;
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings']['icon_providers'] = $enabled;
	}

	public function testRegisterCreatesManager(): void {
		$provider = new IconServiceProvider();
		$provider->register();
		$this->assertNotNull( $provider->getManager() );
	}

	public function testBootWithFontAwesome5Enabled(): void {
		$this->setProviderEnabled( 'fontawesome5' );

		$provider = new IconServiceProvider();
		$provider->register();
		$provider->boot();

		$icons = $provider->getManager()->getAvailableIcons();
		$this->assertArrayHasKey( 'fa5', $icons );
	}

	public function testBootWithFontAwesome6Enabled(): void {
		$this->setProviderEnabled( 'fontawesome6' );

		$provider = new IconServiceProvider();
		$provider->register();
		$provider->boot();

		$icons = $provider->getManager()->getAvailableIcons();
		$this->assertArrayHasKey( 'fa6', $icons );
	}

	public function testBootWithGenericonsEnabled(): void {
		$this->setProviderEnabled( 'genericons' );

		$provider = new IconServiceProvider();
		$provider->register();
		$provider->boot();

		$icons = $provider->getManager()->getAvailableIcons();
		$this->assertArrayHasKey( 'genericons', $icons );
	}

	public function testBootWithBootstrapIconsEnabled(): void {
		$this->setProviderEnabled( 'bootstrap_icons' );

		$provider = new IconServiceProvider();
		$provider->register();
		$provider->boot();

		$icons = $provider->getManager()->getAvailableIcons();
		$this->assertArrayHasKey( 'bootstrap-icons', $icons );
	}

	public function testBootWithAllFourNewProvidersEnabled(): void {
		$this->setProviderEnabled( 'fontawesome5' );
		$this->setProviderEnabled( 'fontawesome6' );
		$this->setProviderEnabled( 'genericons' );
		$this->setProviderEnabled( 'bootstrap_icons' );

		$provider = new IconServiceProvider();
		$provider->register();
		$provider->boot();

		$icons = $provider->getManager()->getAvailableIcons();
		$this->assertCount( 4, $icons );
		$this->assertArrayHasKey( 'fa5', $icons );
		$this->assertArrayHasKey( 'fa6', $icons );
		$this->assertArrayHasKey( 'genericons', $icons );
		$this->assertArrayHasKey( 'bootstrap-icons', $icons );
	}
}
