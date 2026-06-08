<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\Polylang;

use IMedia\Menu\Integrations\Polylang\PolylangIntegration;
use PHPUnit\Framework\TestCase;

final class PolylangIntegrationTest extends TestCase {

	private PolylangIntegration $integration;

	protected function setUp(): void {
		$this->integration = new PolylangIntegration();
	}

	public function testCacheKeyLocaleReturnsPolylangLocale(): void {
		$GLOBALS['__pll_current'] = 'fr_FR';
		$this->assertSame( 'fr_FR', $this->integration->cacheKeyLocale( 'en_US' ) );
	}

	public function testNormalizeLocationsRemovesLangSuffix(): void {
		$locations = array(
			'primary___fr' => 3,
			'primary'      => 1,
			'footer'       => 2,
		);
		$expected  = array(
			'primary' => 1,
			'footer'  => 2,
		);
		$this->assertSame( $expected, $this->integration->normalizeLocations( $locations ) );
		$this->assertArrayNotHasKey( 'primary___fr', $this->integration->normalizeLocations( $locations ) );
	}

	public function testPreviewUrlArgsAddsLang(): void {
		$GLOBALS['__pll_current'] = 'fr';
		$args = $this->integration->previewUrlArgs( array() );
		$this->assertSame( 'fr', $args['lang'] );
	}

	public function testBootRegistersHooks(): void {
		$GLOBALS['__wp_filters'] = array();
		$this->integration->boot();
		$this->assertTrue( has_filter( 'imm_cache_key_locale' ) );
		$this->assertTrue( has_filter( 'imm_nav_menu_locations' ) );
		$this->assertTrue( has_filter( 'imm_preview_url_args' ) );
		$this->assertTrue( has_filter( 'imm_location_assignment_summary' ) );
	}

	public function testLocationAssignmentSummaryShowsPerLanguage(): void {
		$GLOBALS['__pll_languages'] = array( 'en', 'fr' );
		$GLOBALS['_nav_menu_locations'] = array(
			'primary___en' => 5,
			'primary___fr' => 6,
		);
		$GLOBALS['__nav_menus'] = array(
			5 => (object) array( 'name' => 'Main EN' ),
			6 => (object) array( 'name' => 'Main FR' ),
		);
		$result = $this->integration->locationAssignmentSummary( 'Main Menu', 'primary' );
		$this->assertStringContainsString( 'Main EN', $result );
		$this->assertStringContainsString( 'Main FR', $result );
	}
}
