<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Admin\Settings;

use IMedia\Menu\Admin\Settings\Tabs\IconsTab;
use PHPUnit\Framework\TestCase;

final class IconsTabM5Test extends TestCase {

	private IconsTab $tab;

	protected function setUp(): void {
		$this->tab = new IconsTab();
	}

	public function testValidateFontAwesome5ToggleSet(): void {
		$result = $this->tab->validate(
			array(
				'icon_providers' => array( 'fontawesome5' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['fontawesome5'] );
	}

	public function testValidateFontAwesome6ToggleSet(): void {
		$result = $this->tab->validate(
			array(
				'icon_providers' => array( 'fontawesome6' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['fontawesome6'] );
	}

	public function testValidateGenericonsToggleSet(): void {
		$result = $this->tab->validate(
			array(
				'icon_providers' => array( 'genericons' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['genericons'] );
	}

	public function testValidateBootstrapIconsToggleSet(): void {
		$result = $this->tab->validate(
			array(
				'icon_providers' => array( 'bootstrap_icons' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['bootstrap_icons'] );
	}

	public function testValidateFontAwesome5ToggleUnset(): void {
		$result = $this->tab->validate(
			array(
				'icon_providers' => array( 'dashicons' => '1' ),
			)
		);

		$this->assertFalse( $result['icon_providers']['fontawesome5'] );
	}

	public function testSanitizeFontAwesome5ToggleTrue(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array( 'fontawesome5' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['fontawesome5'] );
	}

	public function testSanitizeFontAwesome5ToggleFalse(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array( 'fontawesome5' => '0' ),
			)
		);

		$this->assertFalse( $result['icon_providers']['fontawesome5'] );
	}

	public function testSanitizeFontAwesome6ToggleTrue(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array( 'fontawesome6' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['fontawesome6'] );
	}

	public function testSanitizeGenericonsToggleTrue(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array( 'genericons' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['genericons'] );
	}

	public function testSanitizeBootstrapIconsToggleTrue(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array( 'bootstrap_icons' => '1' ),
			)
		);

		$this->assertTrue( $result['icon_providers']['bootstrap_icons'] );
	}

	public function testSanitizeAllFourNewTogglesDefaultFalse(): void {
		$result = $this->tab->sanitize(
			array(
				'icon_providers' => array(),
			)
		);

		$this->assertArrayHasKey( 'icon_providers', $result );
		$this->assertFalse( $result['icon_providers']['fontawesome5'] );
		$this->assertFalse( $result['icon_providers']['fontawesome6'] );
		$this->assertFalse( $result['icon_providers']['genericons'] );
		$this->assertFalse( $result['icon_providers']['bootstrap_icons'] );
	}
}
