<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\TranslatePress;

use IMedia\Menu\Integrations\TranslatePress\TranslatePressIntegration;
use PHPUnit\Framework\TestCase;

final class TranslatePressIntegrationTest extends TestCase {

	private TranslatePressIntegration $integration;

	protected function setUp(): void {
		$this->integration = new TranslatePressIntegration();
	}

	public function testCacheKeyLocaleReturnsTrpLang(): void {
		$GLOBALS['__trp_lang'] = 'fr_FR';
		$this->assertSame( 'fr_FR', $this->integration->cacheKeyLocale( 'en_US' ) );
	}

	public function testBootRegistersHooks(): void {
		$GLOBALS['__wp_filters'] = array();
		$this->integration->boot();
		$this->assertTrue( has_filter( 'imm_cache_key_locale' ) );
		$this->assertTrue( has_filter( 'imm_location_assignment_summary' ) );
	}
}
