<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\WPML;

use IMedia\Menu\Integrations\WPML\WPMLIntegration;
use PHPUnit\Framework\TestCase;

final class WPMLIntegrationTest extends TestCase {

	private WPMLIntegration $integration;

	protected function setUp(): void {
		$this->integration = new WPMLIntegration();
	}

	public function testCacheKeyLocaleReturnsLanguageCode(): void {
		$this->assertSame( 'en', $this->integration->cacheKeyLocale( 'en_US' ) );
	}

	public function testAddsFlyoutClassToWpmlItems(): void {
		$items = array(
			(object) array( 'classes' => array( 'menu-item', 'wpml-ls-item' ) ),
			(object) array( 'classes' => array( 'menu-item' ) ),
		);
		$result = $this->integration->addLanguageSwitcherClass( $items, new \stdClass() );
		$this->assertContains( 'menu-flyout', $result[0]->classes );
		$this->assertNotContains( 'menu-flyout', $result[1]->classes );
	}

	public function testBootRegistersHooks(): void {
		$GLOBALS['__wp_filters'] = array();
		$this->integration->boot();
		$this->assertTrue( has_filter( 'imm_cache_key_locale' ) );
		$this->assertTrue( has_filter( 'imm_location_assignment_summary' ) );
		$this->assertTrue( has_filter( 'wp_nav_menu_objects' ) );
	}

	public function testLocationSummaryWithActiveLangs(): void {
		$GLOBALS['__nav_menus'] = array(
			5 => (object) array( 'name' => 'Main Menu' ),
		);
		$result = $this->integration->locationAssignmentSummary( 'Main Menu', 'primary' );
		$this->assertStringContainsString( 'Main Menu', $result );
	}
}
