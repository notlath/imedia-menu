<?php

declare(strict_types=1);

namespace IMedia\Menu\Unit\Frontend;

use IMedia\Menu\Frontend\Sticky;
use PHPUnit\Framework\TestCase;

final class StickyTest extends TestCase {

	public function testEmptySettingsReturnsEmptyAttributes(): void {
		$result = Sticky::attributes( array() );
		$this->assertSame( array(), $result );
	}

	public function testStickyDisabledByDefault(): void {
		$result = Sticky::attributes( array( 'sticky' => false ) );
		$this->assertSame( array(), $result );
	}

	public function testStickyEnabledOutputsAllAttributes(): void {
		$settings = array(
			'sticky'            => true,
			'sticky_desktop'    => true,
			'sticky_mobile'     => false,
			'sticky_opacity'    => 0.8,
			'sticky_offset'     => 50,
			'sticky_expand'     => true,
			'sticky_expand_mobile' => false,
		);

		$result = Sticky::attributes( $settings );

		$this->assertSame( 'true', $result['data-sticky-enabled'] );
		$this->assertSame( 'true', $result['data-sticky-desktop'] );
		$this->assertSame( 'false', $result['data-sticky-mobile'] );
		$this->assertSame( '0.8', $result['data-sticky-opacity'] );
		$this->assertSame( '50', $result['data-sticky-offset'] );
		$this->assertSame( 'true', $result['data-sticky-expand'] );
		$this->assertSame( 'false', $result['data-sticky-expand-mobile'] );
	}

	public function testStickyHideUntilScrollUp(): void {
		$settings = array(
			'sticky' => true,
			'sticky_hide_until_scroll_up' => true,
			'sticky_hide_until_scroll_up_tolerance' => 25,
			'sticky_hide_until_scroll_up_offset' => 100,
		);

		$result = Sticky::attributes( $settings );

		$this->assertSame( 'true', $result['data-sticky-hide'] );
		$this->assertSame( '25', $result['data-sticky-hide-tolerance'] );
		$this->assertSame( '100', $result['data-sticky-hide-offset'] );
	}

	public function testStickyHideDefaults(): void {
		$settings = array( 'sticky' => true );

		$result = Sticky::attributes( $settings );

		$this->assertSame( 'false', $result['data-sticky-hide'] );
		$this->assertArrayNotHasKey( 'data-sticky-hide-tolerance', $result );
		$this->assertArrayNotHasKey( 'data-sticky-hide-offset', $result );
	}

	public function testOpacityDefaultsToOne(): void {
		$settings = array( 'sticky' => true );

		$result = Sticky::attributes( $settings );

		$this->assertSame( '1', $result['data-sticky-opacity'] );
	}

	public function testOffsetDefaultsToZero(): void {
		$settings = array( 'sticky' => true );

		$result = Sticky::attributes( $settings );

		$this->assertSame( '0', $result['data-sticky-offset'] );
	}

	public function testMobileExpandRespectsSetting(): void {
		$settings = array(
			'sticky'               => true,
			'sticky_expand'        => false,
			'sticky_expand_mobile' => true,
		);

		$result = Sticky::attributes( $settings );

		$this->assertSame( 'false', $result['data-sticky-expand'] );
		$this->assertSame( 'true', $result['data-sticky-expand-mobile'] );
	}
}
