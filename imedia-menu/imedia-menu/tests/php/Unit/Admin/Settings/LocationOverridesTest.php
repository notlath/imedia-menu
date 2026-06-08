<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Admin\Settings;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use PHPUnit\Framework\TestCase;

final class LocationOverridesTest extends TestCase {

	public function testGetForLocationReturnsEmptyArrayWhenNoOverrides(): void {
		unset( $GLOBALS['_imedia_menu_options']['imedia_menu_location_overrides'] );

		$overrides = LocationOverrides::getForLocation( 'primary' );

		$this->assertIsArray( $overrides );
		$this->assertEmpty( $overrides );
	}

	public function testSetForLocationPersistsOverrides(): void {
		LocationOverrides::setForLocation( 'primary', array( 'orientation' => 'vertical' ) );

		$overrides = LocationOverrides::getForLocation( 'primary' );

		$this->assertSame( 'vertical', $overrides['orientation'] );
	}

	public function testMergeWithGlobalPutsOverridesOnTop(): void {
		$global = array(
			'orientation' => 'horizontal',
			'overlay'     => 'off',
			'menu_bar_bg' => '#ffffff',
		);

		LocationOverrides::setForLocation( 'footer', array( 'orientation' => 'vertical' ) );

		$merged = LocationOverrides::mergeWithGlobal( $global, 'footer' );

		$this->assertSame( 'vertical', $merged['orientation'] );
		$this->assertSame( 'off', $merged['overlay'] );
		$this->assertSame( '#ffffff', $merged['menu_bar_bg'] );
	}

	public function testMergeWithGlobalReturnsGlobalWhenNoOverrides(): void {
		unset( $GLOBALS['_imedia_menu_options']['imedia_menu_location_overrides'] );

		$global = array( 'orientation' => 'horizontal' );
		$merged = LocationOverrides::mergeWithGlobal( $global, 'primary' );

		$this->assertSame( $global, $merged );
	}

	public function testClearForLocationRemovesOverrides(): void {
		LocationOverrides::setForLocation( 'primary', array( 'orientation' => 'accordion' ) );
		LocationOverrides::clearForLocation( 'primary' );

		$overrides = LocationOverrides::getForLocation( 'primary' );

		$this->assertEmpty( $overrides );
	}
}
