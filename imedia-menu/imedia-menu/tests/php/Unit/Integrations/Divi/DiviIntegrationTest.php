<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Integrations\Divi;

use IMedia\Menu\Integrations\Divi\DiviIntegration;
use PHPUnit\Framework\TestCase;

final class DiviIntegrationTest extends TestCase {

	private DiviIntegration $integration;

	protected function setUp(): void {
		$this->integration = new DiviIntegration();
	}

	public function testBootWhenNotDiviThemeDoesNothing(): void {
		$GLOBALS['__wp_filters'] = array();
		$GLOBALS['__wp_actions'] = array();
		$this->integration->boot();
		$this->assertArrayNotHasKey( 'divi_module_library_modules_dependency_tree', $GLOBALS['__wp_filters'] );
	}

	public function testRegisterModuleAddsPath(): void {
		$modules = array();
		$result  = $this->integration->registerModule( $modules );
		$this->assertCount( 1, $result );
		$this->assertStringContainsString( 'location/Module.php', $result[0] );
	}
}
