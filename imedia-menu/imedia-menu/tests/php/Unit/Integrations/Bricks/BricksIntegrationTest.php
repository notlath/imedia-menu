<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\Bricks;

use IMedia\Menu\Integrations\Bricks\BricksIntegration;
use PHPUnit\Framework\TestCase;

final class BricksIntegrationTest extends TestCase {

	private BricksIntegration $integration;

	protected function setUp(): void {
		$this->integration = new BricksIntegration();
	}

	public function testBootWhenNotBricksThemeDoesNothing(): void {
		$GLOBALS['__wp_actions'] = array();
		$this->integration->boot();
		$this->assertArrayNotHasKey( 'init', $GLOBALS['__wp_actions'] );
	}
}
