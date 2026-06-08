<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\Elementor;

use IMedia\Menu\Integrations\Elementor\ElementorIntegration;
use PHPUnit\Framework\TestCase;

final class ElementorIntegrationTest extends TestCase {

	private ElementorIntegration $integration;

	protected function setUp(): void {
		$this->integration = new ElementorIntegration();
	}

	public function testBootWhenElementorNotActiveDoesNothing(): void {
		$GLOBALS['__wp_actions'] = array();
		$this->integration->boot();
		$this->assertArrayNotHasKey( 'elementor/widgets/register', $GLOBALS['__wp_actions'] );
	}
}
