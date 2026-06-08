<?php

declare(strict_types=1);

namespace IMedia\Menu\Unit\Frontend;

use IMedia\Menu\Frontend\StyleOverrides;
use PHPUnit\Framework\TestCase;

final class StyleOverridesTest extends TestCase {

	protected function setUp(): void {
		$GLOBALS['_post_meta'] = array();
	}

	protected function tearDown(): void {
		$GLOBALS['_post_meta'] = array();
	}

	public function testPropertiesHasCorrectCount(): void {
		$this->assertGreaterThanOrEqual( 35, count( StyleOverrides::PROPERTIES ) );
		$this->assertLessThanOrEqual( 50, count( StyleOverrides::PROPERTIES ) );
	}

	public function testCssVarForKnownProperty(): void {
		$this->assertSame( '--imm-item-bg-from', StyleOverrides::cssVarFor( 'menu_item_background_from' ) );
		$this->assertSame( '--imm-item-color', StyleOverrides::cssVarFor( 'menu_item_link_color' ) );
		$this->assertSame( '--imm-item-weight', StyleOverrides::cssVarFor( 'menu_item_link_weight' ) );
	}

	public function testCssVarForUnknownProperty(): void {
		$this->assertNull( StyleOverrides::cssVarFor( 'unknown_property' ) );
	}

	public function testSanitizeColorAcceptsHex(): void {
		$this->assertSame( '#ffffff', StyleOverrides::sanitizeValue( 'menu_item_link_color', '#ffffff' ) );
		$this->assertSame( '#fff', StyleOverrides::sanitizeValue( 'menu_item_link_color', '#fff' ) );
		$this->assertSame( '#ABCDEF', StyleOverrides::sanitizeValue( 'menu_item_link_color', '#ABCDEF' ) );
	}

	public function testSanitizeColorAcceptsRgb(): void {
		$this->assertSame( 'rgb(255,0,0)', StyleOverrides::sanitizeValue( 'menu_item_link_color', 'rgb(255,0,0)' ) );
		$this->assertSame( 'rgba(0,0,0,0.5)', StyleOverrides::sanitizeValue( 'menu_item_link_color', 'rgba(0,0,0,0.5)' ) );
	}

	public function testSanitizeColorRejectsInvalid(): void {
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_color', 'red' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_color', 'notacolor' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_color', '#zzz' ) );
	}

	public function testSanitizePixelValue(): void {
		$this->assertSame( '10px', StyleOverrides::sanitizeValue( 'menu_item_font_size', '10px' ) );
		$this->assertSame( '1.5em', StyleOverrides::sanitizeValue( 'menu_item_padding_left', '1.5em' ) );
		$this->assertSame( '50%', StyleOverrides::sanitizeValue( 'panel_width', '50%' ) );
	}

	public function testSanitizeRejectsInvalidPixel(): void {
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_font_size', 'ten' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_font_size', '10xyz' ) );
	}

	public function testSanitizeWeightAcceptsValidValues(): void {
		$this->assertSame( 'bold', StyleOverrides::sanitizeValue( 'menu_item_link_weight', 'bold' ) );
		$this->assertSame( 'normal', StyleOverrides::sanitizeValue( 'menu_item_link_weight', 'normal' ) );
		$this->assertSame( '700', StyleOverrides::sanitizeValue( 'menu_item_link_weight', '700' ) );
		$this->assertSame( 'inherit', StyleOverrides::sanitizeValue( 'menu_item_link_weight', 'inherit' ) );
	}

	public function testSanitizeWeightRejectsInvalid(): void {
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_weight', 'super-bold' ) );
	}

	public function testSanitizeTextAlign(): void {
		$this->assertSame( 'left', StyleOverrides::sanitizeValue( 'menu_item_link_text_align', 'left' ) );
		$this->assertSame( 'center', StyleOverrides::sanitizeValue( 'menu_item_link_text_align', 'center' ) );
		$this->assertSame( 'right', StyleOverrides::sanitizeValue( 'menu_item_link_text_align', 'right' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_text_align', 'justify' ) );
	}

	public function testSanitizeTextTransform(): void {
		$this->assertSame( 'uppercase', StyleOverrides::sanitizeValue( 'menu_item_link_text_transform', 'uppercase' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_text_transform', 'wiggle' ) );
	}

	public function testSanitizeTextDecoration(): void {
		$this->assertSame( 'underline', StyleOverrides::sanitizeValue( 'menu_item_link_text_decoration', 'underline' ) );
		$this->assertSame( '', StyleOverrides::sanitizeValue( 'menu_item_link_text_decoration', 'blink' ) );
	}

	public function testGetItemStylesEmpty(): void {
		$result = StyleOverrides::getItemStyles( 1 );
		$this->assertSame( '', $result );
	}

	public function testGetItemStylesReturnsCustomProperties(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_styles_enabled' => array( 'menu_item_background_from', 'menu_item_link_color', 'menu_item_font_size' ),
			'_imedia_menu_styles_values'  => array(
				'menu_item_background_from' => '#ff0000',
				'menu_item_link_color'      => '#000000',
				'menu_item_font_size'       => '16px',
			),
		);

		$result = StyleOverrides::getItemStyles( 1 );

		$this->assertStringContainsString( '--imm-item-bg-from:#ff0000', $result );
		$this->assertStringContainsString( '--imm-item-color:#000000', $result );
		$this->assertStringContainsString( '--imm-item-font-size:16px', $result );
	}

	public function testGetItemStylesIgnoresInvalidValues(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_styles_enabled' => array( 'menu_item_link_color' ),
			'_imedia_menu_styles_values'  => array(
				'menu_item_link_color' => 'notacolor',
			),
		);

		$result = StyleOverrides::getItemStyles( 1 );
		$this->assertSame( '', $result );
	}

	public function testGetItemStylesIgnoresUnknownProperties(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_styles_enabled' => array( 'menu_item_link_color', 'unknown_property' ),
			'_imedia_menu_styles_values'  => array(
				'menu_item_link_color' => '#000',
				'unknown_property'     => 'foo',
			),
		);

		$result = StyleOverrides::getItemStyles( 1 );

		$this->assertStringContainsString( '--imm-item-color:#000', $result );
		$this->assertStringNotContainsString( 'unknown', $result );
	}
}
