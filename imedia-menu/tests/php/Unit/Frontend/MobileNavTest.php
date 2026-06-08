<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend;

use IMedia\Menu\Frontend\MobileNav;
use PHPUnit\Framework\TestCase;

final class MobileNavTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
		parent::tearDown();
	}

	public function testRenderOffCanvasWithEnabledTrue(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$nav = new MobileNav();
		ob_start();
		$nav->renderOffCanvas();
		$output = ob_get_clean();
		$this->assertStringContainsString( 'imm-overlay', $output );
		$this->assertStringContainsString( 'imm-mobile-nav', $output );
	}

	public function testRenderOffCanvasWithEnabledFalseReturnsEarly(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => false ) );
		$nav = new MobileNav();
		ob_start();
		$nav->renderOffCanvas();
		$output = ob_get_clean();
		$this->assertSame( '', $output );
	}

	public function testRenderOffCanvasContainsExpectedElements(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$nav = new MobileNav();
		ob_start();
		$nav->renderOffCanvas();
		$output = ob_get_clean();
		$this->assertStringContainsString( 'role="dialog"', $output );
		$this->assertStringContainsString( 'aria-modal="true"', $output );
		$this->assertStringContainsString( 'dashicons-no', $output );
	}
}
