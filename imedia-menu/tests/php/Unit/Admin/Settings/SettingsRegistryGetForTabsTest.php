<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Admin\Settings;

use IMedia\Menu\Admin\Settings\SettingsRegistry;
use PHPUnit\Framework\TestCase;

final class SettingsRegistryGetForTabsTest extends TestCase {

	private SettingsRegistry $registry;

	protected function setUp(): void {
		$this->registry = new SettingsRegistry();
	}

	public function testGetForTabsReturnsSubset(): void {
		$result = $this->registry->getForTabs( array( 'general', 'mobile' ) );

		$this->assertCount( 2, $result );
		$this->assertArrayHasKey( 'general', $result );
		$this->assertArrayHasKey( 'mobile', $result );
	}

	public function testGetForTabsReturnsAllForFullList(): void {
		$result = $this->registry->getForTabs( array(
			'general', 'design', 'animations', 'mobile', 'visibility',
			'icons', 'fonts', 'performance', 'advanced',
		) );

		$this->assertCount( 9, $result );
	}

	public function testGetForTabsExcludesUnknownTabs(): void {
		$result = $this->registry->getForTabs( array( 'general', 'nonexistent' ) );

		$this->assertCount( 1, $result );
		$this->assertArrayHasKey( 'general', $result );
		$this->assertArrayNotHasKey( 'nonexistent', $result );
	}

	public function testGetForTabsReturnsEmptyForEmptyList(): void {
		$result = $this->registry->getForTabs( array() );

		$this->assertSame( array(), $result );
	}

	public function testGetForTabsReturnsInstances(): void {
		$result = $this->registry->getForTabs( array( 'advanced' ) );

		$this->assertArrayHasKey( 'advanced', $result );
		$this->assertSame( 'advanced', $result['advanced']->id() );
	}
}
