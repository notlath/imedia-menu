<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\Breakdance;

use IMedia\Menu\Integrations\Breakdance\BreakdanceIntegration;
use PHPUnit\Framework\TestCase;

final class BreakdanceIntegrationTest extends TestCase {

	private BreakdanceIntegration $integration;

	protected function setUp(): void {
		$this->integration = new BreakdanceIntegration();
	}

	public function testBootWhenBreakdanceNotActiveDoesNothing(): void {
		$GLOBALS['__wp_actions'] = array();
		$this->integration->boot();
		$this->assertArrayNotHasKey( 'init', $GLOBALS['__wp_actions'] );
	}
}
