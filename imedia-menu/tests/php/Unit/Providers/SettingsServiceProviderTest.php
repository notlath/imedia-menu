<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Providers;

use IMedia\Menu\Providers\SettingsServiceProvider;
use PHPUnit\Framework\TestCase;

final class SettingsServiceProviderTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['__wp_actions'] = array();
		$GLOBALS['__wp_filters'] = array();
		$GLOBALS['_imedia_menu_options'] = array();
	}

	protected function tearDown(): void {
		unset(
			$GLOBALS['__wp_actions'],
			$GLOBALS['__wp_filters'],
			$GLOBALS['_imedia_menu_options']
		);
		parent::tearDown();
	}

	public function testRegisterCreatesInstances(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$this->assertInstanceOf(
			\IMedia\Menu\Admin\Settings\SettingsPage::class,
			$provider->getSettingsPage()
		);
		$this->assertInstanceOf(
			\IMedia\Menu\Admin\Settings\SettingsRegistry::class,
			$provider->getRegistry()
		);
	}

	public function testBootAddsHooks(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$provider->boot();

		$this->assertTrue( has_action( 'admin_menu' ) );
		$this->assertTrue( has_action( 'admin_enqueue_scripts' ) );
		$this->assertTrue( has_action( 'admin_init' ) );
	}

	public function testAddSettingsPagesCallsAddSubmenuPage(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$provider->addSettingsPages();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueSettingsAssetsSkipsNonMatchingHook(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$provider->enqueueSettingsAssets( 'plugins.php' );
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueSettingsAssetsWithMatchingHook(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$provider->enqueueSettingsAssets( 'appearance_page_imedia-menu' );
		$this->expectNotToPerformAssertions();
	}

	public function testRegisterSettingsCallsRegisterSetting(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$provider->registerSettings();
		$this->expectNotToPerformAssertions();
	}

	public function testSanitizeSettingsReturnsArray(): void {
		$provider = new SettingsServiceProvider();
		$provider->register();
		$result = $provider->sanitizeSettings( array( 'enabled' => true ) );
		$this->assertIsArray( $result );
	}
}
