<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend;

use IMedia\Menu\Frontend\Assets;
use PHPUnit\Framework\TestCase;

final class AssetsTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
		parent::tearDown();
	}

	public function testEnqueueWithEnabledTrue(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => true ) );
		$assets = new Assets();
		$assets->enqueue();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueWithEnabledFalseReturnsEarly(): void {
		update_option( 'imedia_menu_settings', array( 'enabled' => false ) );
		$assets = new Assets();
		$assets->enqueue();
		$this->expectNotToPerformAssertions();
	}

	public function testRegisterTabbedScript(): void {
		$assets = new Assets();
		$assets->registerTabbedScript();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueCustomCssNoFile(): void {
		$assets = new Assets();
		$assets->enqueueCustomCss();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueConditionalAssets(): void {
		$assets = new Assets();
		$assets->enqueueConditionalAssets();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueLayoutAssetsWithHorizontal(): void {
		$assets = new Assets();
		$assets->enqueueLayoutAssets( array( 'orientation' => 'horizontal' ) );
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueLayoutAssetsWithVertical(): void {
		$assets = new Assets();
		$assets->enqueueLayoutAssets( array( 'orientation' => 'vertical' ) );
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueToggleBarAssetsNoBlocks(): void {
		$assets = new Assets();
		$assets->enqueueToggleBarAssets();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueStickyAssetsDisabled(): void {
		$assets = new Assets();
		$assets->enqueueStickyAssets( array( 'sticky' => false ) );
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueStickyAssetsEnabled(): void {
		$assets = new Assets();
		$assets->enqueueStickyAssets( array( 'sticky' => true ) );
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueBadgeAssetsNoBadges(): void {
		$assets = new Assets();
		$assets->enqueueBadgeAssets();
		$this->expectNotToPerformAssertions();
	}

	public function testEnqueueStyleOverridesAssetsNone(): void {
		$assets = new Assets();
		$assets->enqueueStyleOverridesAssets();
		$this->expectNotToPerformAssertions();
	}

	public function testGenerateCustomCssCreatesFile(): void {
		$assets = new Assets();
		$result = $assets->generateCustomCss( array(
			'font_family'         => 'Arial',
			'font_size'           => 16,
			'font_weight'         => 400,
			'line_height'         => 1.5,
			'menu_bar_height'     => 60,
			'item_padding'        => '10px',
			'panel_padding'       => '20px',
			'item_gap'            => 5,
			'animation_duration'   => 200,
			'z_index'             => 100,
		) );

		$this->assertFalse( $result );
	}

	public function testMaybeInlineCustomCss(): void {
		$assets = new Assets();
		$assets->maybeInlineCustomCss();
		$this->expectNotToPerformAssertions();
	}

	public function testBuildInlineCssEmptySettings(): void {
		$this->assertSame( '', Assets::buildInlineCss( array() ) );
	}

	public function testBuildInlineCssWithMenuBarBg(): void {
		$css = Assets::buildInlineCss( array( 'menu_bar_bg' => '#ff0000' ) );
		$this->assertStringContainsString( '--imm-bg', $css );
		$this->assertStringContainsString( '#ff0000', $css );
	}

	public function testBuildInlineCssWithTextColor(): void {
		$css = Assets::buildInlineCss( array( 'menu_text_color' => '#333333' ) );
		$this->assertStringContainsString( '--imm-text', $css );
		$this->assertStringContainsString( '#333333', $css );
	}

	public function testBuildInlineCssWithDropdownBg(): void {
		$css = Assets::buildInlineCss( array( 'dropdown_bg' => '#ffffff' ) );
		$this->assertStringContainsString( '--imm-dropdown-bg', $css );
	}

	public function testBuildInlineCssWithAllLightFields(): void {
		$css = Assets::buildInlineCss( array(
			'menu_bar_bg'     => '#222',
			'menu_text_color' => '#fff',
			'menu_text_hover' => '#ccc',
			'dropdown_bg'     => '#333',
		) );

		$this->assertStringContainsString( '.imm-nav', $css );
		$this->assertStringContainsString( '#222', $css );
		$this->assertStringContainsString( '#fff', $css );
		$this->assertStringContainsString( '#ccc', $css );
		$this->assertStringContainsString( '#333', $css );
	}

	public function testBuildInlineCssWithDarkMode(): void {
		$css = Assets::buildInlineCss( array(
			'menu_bar_bg'              => '#fff',
			'dark_mode_enabled'         => true,
			'dark_mode_bg'              => '#111',
			'dark_mode_text'            => '#eee',
			'dark_mode_text_hover'      => '#ccc',
			'dark_mode_dropdown_bg'     => '#222',
			'dark_mode_dropdown_border' => '#444',
		) );

		$this->assertStringContainsString( '@media (prefers-color-scheme: dark)', $css );
		$this->assertStringContainsString( '#111', $css );
		$this->assertStringContainsString( '#eee', $css );
		$this->assertStringContainsString( '#444', $css );
	}

	public function testBuildInlineCssDarkModeWithoutLightMode(): void {
		$css = Assets::buildInlineCss( array(
			'dark_mode_enabled' => true,
			'dark_mode_bg'      => '#000',
		) );

		$this->assertStringContainsString( 'dark', $css );
		$this->assertStringNotContainsString( '--imm-text', $css );
	}

	public function testBuildInlineCssNoDarkModeDarkFieldsIgnored(): void {
		$css = Assets::buildInlineCss( array(
			'menu_bar_bg'    => '#fff',
			'dark_mode_bg'   => '#000',
		) );

		$this->assertStringNotContainsString( 'prefers-color-scheme', $css );
		$this->assertStringContainsString( '#fff', $css );
	}
}
