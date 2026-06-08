<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend;

use IMedia\Menu\Enums\MenuOrientation;
use IMedia\Menu\Enums\OverlayMode;
use IMedia\Menu\Frontend\Overlay;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class OverlayTest extends TestCase {

	private function clearNavMenus(): void {
		$GLOBALS['_wp_registered_nav_menus'] = array();
		$GLOBALS['_nav_menu_locations']     = array();
		unset( $GLOBALS['_imedia_menu_options'] );
	}

	private function makeOverlay(): Overlay {
		$overlay = new Overlay();
		$refl    = new ReflectionClass( $overlay );
		return $overlay;
	}

	public function testOverlayModeOffDoesNotEmitDiv(): void {
		$this->clearNavMenus();
		$GLOBALS['_wp_registered_nav_menus'] = array( 'primary' => 'Primary' );
		$GLOBALS['_nav_menu_locations']     = array( 'primary' => 1 );
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings'] = array( 'overlay' => 'off' );

		$overlay = $this->makeOverlay();

		ob_start();
		$overlay->render();
		$output = ob_get_clean();

		$this->assertStringNotContainsString( 'imm-page-overlay', $output );
	}

	public function testOverlayModeBothEmitsDivAndScript(): void {
		$this->clearNavMenus();
		$GLOBALS['_wp_registered_nav_menus'] = array( 'primary' => 'Primary' );
		$GLOBALS['_nav_menu_locations']     = array( 'primary' => 1 );
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings'] = array( 'overlay' => 'both', 'overlay_color' => 'rgba(0,0,0,0.5)' );

		$overlay = $this->makeOverlay();

		ob_start();
		$overlay->render();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'imm-page-overlay', $output );
		$this->assertStringContainsString( 'data-imm-overlay="both"', $output );
		$this->assertStringContainsString( '--imm-overlay-color:rgba(0,0,0,0.5)', $output );
		$this->assertStringContainsString( 'imm-overlay-watcher', $output );
	}

	public function testOverlayModeDesktopReadsCorrectly(): void {
		$this->assertSame( 'desktop', OverlayMode::Desktop->value );
		$this->assertSame( 'min-width:769px', $this->expectedBreakpoint( OverlayMode::Desktop ) );
	}

	public function testOverlayModeMobileReadsCorrectly(): void {
		$this->assertSame( 'mobile', OverlayMode::Mobile->value );
		$this->assertSame( 'max-width:768px', $this->expectedBreakpoint( OverlayMode::Mobile ) );
	}

	public function testOverlayModeBothReadsCorrectly(): void {
		$this->assertSame( 'both', OverlayMode::Both->value );
		$this->assertSame( 'all', $this->expectedBreakpoint( OverlayMode::Both ) );
	}

	public function testMenuOrientationEnums(): void {
		$this->assertSame( 'horizontal', MenuOrientation::Horizontal->value );
		$this->assertSame( 'vertical', MenuOrientation::Vertical->value );
		$this->assertSame( 'accordion', MenuOrientation::Accordion->value );
		$this->assertSame( 'click', MenuOrientation::Accordion->requiredTriggerType() );
		$this->assertNull( MenuOrientation::Horizontal->requiredTriggerType() );
		$this->assertNull( MenuOrientation::Vertical->requiredTriggerType() );
	}

	private function expectedBreakpoint( OverlayMode $mode ): string {
		return match ( $mode ) {
			OverlayMode::Desktop => 'min-width:769px',
			OverlayMode::Mobile  => 'max-width:768px',
			OverlayMode::Both    => 'all',
			OverlayMode::Off     => 'all',
		};
	}
}
