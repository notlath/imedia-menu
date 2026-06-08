<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Providers;

use IMedia\Menu\Providers\RestApiServiceProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class RestApiServiceProviderSanitizeTest extends TestCase {

	private function callSanitize( array $overrides ): array {
		$provider = new RestApiServiceProvider();
		$refl     = new ReflectionClass( $provider );
		$method   = $refl->getMethod( 'sanitizeLocationOverrides' );
		$method->setAccessible( true );

		return $method->invoke( $provider, $overrides );
	}

	public function testOrientationWhitelistedValues(): void {
		$result = $this->callSanitize( array( 'orientation' => 'horizontal' ) );
		$this->assertSame( 'horizontal', $result['orientation'] );

		$result = $this->callSanitize( array( 'orientation' => 'vertical' ) );
		$this->assertSame( 'vertical', $result['orientation'] );

		$result = $this->callSanitize( array( 'orientation' => 'accordion' ) );
		$this->assertSame( 'accordion', $result['orientation'] );
	}

	public function testInvalidOrientationDropped(): void {
		$result = $this->callSanitize( array( 'orientation' => 'sideways' ) );
		$this->assertArrayNotHasKey( 'orientation', $result );
	}

	public function testOverlayWhitelistedValues(): void {
		foreach ( array( 'off', 'desktop', 'mobile', 'both' ) as $mode ) {
			$result = $this->callSanitize( array( 'overlay' => $mode ) );
			$this->assertSame( $mode, $result['overlay'] );
		}
	}

	public function testInvalidOverlayDropped(): void {
		$result = $this->callSanitize( array( 'overlay' => 'always' ) );
		$this->assertArrayNotHasKey( 'overlay', $result );
	}

	public function testOverlayColorIsTextField(): void {
		$result = $this->callSanitize( array( 'overlay_color' => 'rgba(0,0,0,0.3)' ) );
		$this->assertSame( 'rgba(0,0,0,0.3)', $result['overlay_color'] );

		// sanitize_text_field strips newlines and tabs.
		$result = $this->callSanitize( array( 'overlay_color' => "value\nwith\twhitespace" ) );
		$this->assertStringNotContainsString( "\n", $result['overlay_color'] );
		$this->assertStringNotContainsString( "\t", $result['overlay_color'] );
	}

	public function testStickyAndTransparentCoercedToBool(): void {
		$result = $this->callSanitize( array( 'sticky' => 1, 'transparent_mode' => 'on' ) );
		$this->assertTrue( $result['sticky'] );
		$this->assertTrue( $result['transparent_mode'] );

		$result = $this->callSanitize( array( 'sticky' => 0 ) );
		$this->assertFalse( $result['sticky'] );
	}

	public function testTriggerTypeWhitelisted(): void {
		foreach ( array( 'hover', 'click', 'hover_click' ) as $type ) {
			$result = $this->callSanitize( array( 'trigger_type' => $type ) );
			$this->assertSame( $type, $result['trigger_type'] );
		}

		$result = $this->callSanitize( array( 'trigger_type' => 'magic' ) );
		$this->assertArrayNotHasKey( 'trigger_type', $result );
	}
}
