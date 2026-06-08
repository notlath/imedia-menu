<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Providers;

use IMedia\Menu\Providers\FrontendServiceProvider;
use PHPUnit\Framework\TestCase;

final class FrontendServiceProviderTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options']  = array();
		$GLOBALS['_nav_menu_locations']   = array();
		$GLOBALS['_wp_registered_nav_menus'] = array();
		$GLOBALS['__nav_menus']           = array();
		$this->clearHooks();
	}

	protected function tearDown(): void {
		$this->clearHooks();
		unset(
			$GLOBALS['_imedia_menu_options'],
			$GLOBALS['_nav_menu_locations'],
			$GLOBALS['_wp_registered_nav_menus'],
			$GLOBALS['__nav_menus']
		);
		parent::tearDown();
	}

	private function clearHooks(): void {
		$GLOBALS['__wp_actions'] = array();
		$GLOBALS['__wp_filters'] = array();
	}

	public function testRegisterCreatesInstances(): void {
		$provider = new FrontendServiceProvider();
		$provider->register();
		$this->expectNotToPerformAssertions();
	}

	public function testBootAddsHooks(): void {
		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->boot();

		$this->assertTrue( has_action( 'wp_enqueue_scripts' ) );
		$this->assertTrue( has_filter( 'wp_nav_menu_args' ) );
	}

	public function testFilterMenuArgsEnabledReturnsModifiedArgs(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'            => true,
			'orientation'        => 'horizontal',
			'hover_delay'        => 200,
			'default_animation'  => 'fade',
			'enable_caching'     => false,
		) );

		$GLOBALS['_nav_menu_locations']         = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5]              = (object) array( 'name' => 'Primary Menu' );

		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->boot();

		$args = array(
			'theme_location' => 'primary',
			'container'      => 'div',
			'menu_class'     => 'menu',
			'echo'           => true,
		);

		$result = $provider->filterMenuArgs( $args );

		$this->assertArrayHasKey( 'walker', $result );
		$this->assertInstanceOf( \IMedia\Menu\Frontend\MenuWalker::class, $result['walker'] );
		$this->assertSame( 'nav', $result['container'] );
		$this->assertStringContainsString( 'imm-nav', $result['container_class'] );
		$this->assertStringContainsString( 'imm-menu', $result['menu_class'] );
		$this->assertSame( false, $result['echo'] );
		$this->assertSame( false, $result['fallback_cb'] );
		$this->assertStringContainsString( 'imm-menu-5', $result['items_wrap'] );
		$this->assertStringContainsString( 'Primary Menu', $result['container_aria_label'] );
	}

	public function testFilterMenuArgsDisabledReturnsUnchanged(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => false ) );
		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->boot();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );
		$this->assertSame( $args, $result );
	}

	public function testFilterMenuArgsNoLocationReturnsUnchanged(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->boot();

		$args   = array();
		$result = $provider->filterMenuArgs( $args );
		$this->assertSame( $args, $result );
	}

	public function testFilterMenuArgsNoMenuIdReturnsUnchanged(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 0 );
		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->boot();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );
		$this->assertSame( $args, $result );
	}

	public function testFilterMenuArgsWithTransparentSetting(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'           => true,
			'transparent_mode'  => true,
			'orientation'       => 'horizontal',
		) );

		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5]      = (object) array( 'name' => 'Main' );

		$provider = new FrontendServiceProvider();
		$provider->register();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );

		$this->assertStringContainsString( 'imm-nav--transparent', $result['container_class'] );
	}

	public function testFilterMenuArgsWithStickySetting(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'     => true,
			'sticky'      => true,
			'orientation' => 'horizontal',
		) );

		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5]      = (object) array( 'name' => 'Main' );

		$provider = new FrontendServiceProvider();
		$provider->register();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );

		$this->assertStringContainsString( 'imm-nav--sticky', $result['container_class'] );
	}

	public function testFilterMenuArgsWithOverlaySetting(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'     => true,
			'overlay'     => 'full',
			'orientation' => 'horizontal',
		) );

		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5]      = (object) array( 'name' => 'Main' );

		$provider = new FrontendServiceProvider();
		$provider->register();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );

		$this->assertStringContainsString( 'imm-nav--overlay-full', $result['container_class'] );
	}

	public function testFilterMenuArgsWithVerticalOrientation(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'     => true,
			'orientation' => 'vertical',
		) );

		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5]      = (object) array( 'name' => 'Main' );

		$provider = new FrontendServiceProvider();
		$provider->register();

		$args   = array( 'theme_location' => 'primary' );
		$result = $provider->filterMenuArgs( $args );

		$this->assertStringContainsString( 'imm-nav--vertical', $result['container_class'] );
		$this->assertStringContainsString( 'imm-menu--vertical', $result['menu_class'] );
	}

	public function testEnqueuePerLocationInlineCssNoRegisteredMenus(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->enqueuePerLocationInlineCss();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueuePerLocationInlineCssWithRegisteredMenus(): void {
		update_option( 'imedia_menu_settings', array(
			'enabled'        => true,
			'menu_bar_bg'    => '#ff0000',
			'menu_text_color' => '#ffffff',
		) );

		$GLOBALS['_wp_registered_nav_menus'] = array( 'primary' => 'Primary Menu' );
		$GLOBALS['_nav_menu_locations']      = array( 'primary' => 5 );

		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->enqueuePerLocationInlineCss();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueuePerLocationInlineCssDisabled(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => false ) );
		$GLOBALS['_wp_registered_nav_menus'] = array( 'primary' => 'Primary Menu' );

		$provider = new FrontendServiceProvider();
		$provider->register();
		$provider->enqueuePerLocationInlineCss();
		$this->expectNotToPerformAssertions();
	}
}
