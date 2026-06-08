<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Blocks;

use IMedia\Menu\Blocks\Navigation\EditorPreview;
use IMedia\Menu\Blocks\Navigation\Navigation;
use PHPUnit\Framework\TestCase;

final class NavigationBlockTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options'] = array();
		$GLOBALS['_nav_menu_locations']  = array();
		$GLOBALS['__nav_menus']          = array();
		$GLOBALS['__wp_filters']         = array();
		$GLOBALS['__wp_actions']         = array();
	}

	protected function tearDown(): void {
		unset(
			$GLOBALS['_imedia_menu_options'],
			$GLOBALS['_nav_menu_locations'],
			$GLOBALS['__nav_menus'],
			$GLOBALS['__wp_filters'],
			$GLOBALS['__wp_actions']
		);
		parent::tearDown();
	}

	public function testRenderReturnsPlaceholderForNoMenuId(): void {
		$result = Navigation::render(
			array(
				'menuId'    => 0,
				'className' => '',
			),
			''
		);
		$this->assertStringContainsString( 'Select a menu', $result );
	}

	public function testRenderReturnsNotFoundForInvalidMenuId(): void {
		$result = Navigation::render(
			array(
				'menuId'    => 999,
				'className' => '',
			),
			''
		);
		$this->assertStringContainsString( 'not found', $result );
	}

	public function testRenderIncludesClassName(): void {
		$result = Navigation::render(
			array(
				'menuId'    => 0,
				'className' => 'my-custom-class',
			),
			''
		);
		$this->assertStringContainsString( 'my-custom-class', $result );
	}

	public function testRegisterCallsRegisterBlockType(): void {
		Navigation::register();
		$this->expectNotToPerformAssertions();
	}

	public function testRenderWithValidMenuDoesNotThrow(): void {
		$GLOBALS['__nav_menus'][5] = (object) array( 'name' => 'Primary Menu' );
		$result                    = Navigation::render(
			array(
				'menuId'    => 5,
				'className' => '',
			),
			''
		);
		$this->assertNotNull( $result );
	}

	public function testRenderWithValidMenuReturnsString(): void {
		$GLOBALS['__nav_menus'][7] = (object) array( 'name' => 'Footer Menu' );
		$result                    = Navigation::render(
			array(
				'menuId'    => 7,
				'className' => '',
			),
			''
		);
		$this->assertIsString( $result );
	}

	public function testRenderMergesLocationOverridesWhenLocationFound(): void {
		$GLOBALS['__nav_menus'][3]      = (object) array( 'name' => 'Main Menu' );
		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 3 );
		update_option(
			'imedia_menu_settings',
			array(
				'enabled'     => true,
				'orientation' => 'horizontal',
			)
		);
		update_option(
			'imedia_menu_location_overrides',
			array(
				'primary' => array( 'orientation' => 'vertical' ),
			)
		);

		$result = Navigation::render(
			array(
				'menuId'    => 3,
				'className' => '',
			),
			''
		);
		$this->assertIsString( $result );
	}

	public function testRenderUsesGlobalSettingsWhenNoLocation(): void {
		$GLOBALS['__nav_menus'][4] = (object) array( 'name' => 'Unassigned Menu' );
		update_option(
			'imedia_menu_settings',
			array(
				'enabled'     => true,
				'orientation' => 'horizontal',
			)
		);

		$result = Navigation::render(
			array(
				'menuId'    => 4,
				'className' => '',
			),
			''
		);
		$this->assertIsString( $result );
	}

	public function testMaybePrependMobileToggleAddsButtonWhenNoToggleBar(): void {
		$args = (object) array(
			'container_class' => 'imm-nav',
			'menu'            => (object) array( 'term_id' => 5 ),
		);

		$result = Navigation::maybePrependMobileToggle( 'menu-items', $args );
		$this->assertStringContainsString( 'imm-mobile-toggle', $result );
	}

	public function testMaybePrependMobileToggleSkipsWhenToggleBarExists(): void {
		$GLOBALS['_nav_menu_locations'] = array( 'main-menu' => 10 );
		$GLOBALS['__nav_menus'][10]     = (object) array(
			'term_id' => 10,
			'name'    => 'Main',
		);
		update_option(
			'imedia_menu_toggle_bar',
			array(
				'main-menu' => array(
					'blocks' => array(
						array(
							'id'       => 'block-1',
							'type'     => 'menu_toggle',
							'align'    => 'left',
							'settings' => array(),
						),
					),
				),
			)
		);

		$args = (object) array(
			'container_class' => 'imm-nav',
			'menu'            => (object) array( 'term_id' => 10 ),
		);

		$result = Navigation::maybePrependMobileToggle( 'menu-items', $args );
		$this->assertStringNotContainsString( 'imm-mobile-toggle', $result );
	}

	public function testMaybePrependMobileToggleSkipsNonImNav(): void {
		$args = (object) array(
			'container_class' => 'other-class',
			'menu'            => (object) array( 'term_id' => 5 ),
		);

		$result = Navigation::maybePrependMobileToggle( 'menu-items', $args );
		$this->assertSame( 'menu-items', $result );
	}

	public function testEditorPreviewGetMenuOptionsReturnsArray(): void {
		$options = EditorPreview::getMenuOptions();
		$this->assertIsArray( $options );
		$this->assertSame( 0, $options[0]['value'] );
		$this->assertStringContainsString( 'Select a menu', $options[0]['label'] );
	}

	public function testEditorPreviewGetMenuOptionsIncludesMenus(): void {
		$GLOBALS['__nav_menus'][2] = (object) array(
			'term_id' => 2,
			'name'    => 'Primary',
		);
		$GLOBALS['__nav_menus'][3] = (object) array(
			'term_id' => 3,
			'name'    => 'Footer',
		);

		$options = EditorPreview::getMenuOptions();
		$this->assertCount( 3, $options );
	}

	public function testEditorPreviewGetMenuOptionsShowsLocationWhenAssigned(): void {
		$GLOBALS['__nav_menus'][2]      = (object) array(
			'term_id' => 2,
			'name'    => 'Primary',
		);
		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 2 );

		$options = EditorPreview::getMenuOptions();
		$this->assertStringContainsString( 'primary', $options[1]['label'] );
	}

	public function testEditorPreviewGetPreviewHtmlWithNoMenu(): void {
		$result = EditorPreview::getPreviewHtml( 0 );
		$this->assertStringContainsString( 'Select a menu', $result );
	}

	public function testEditorPreviewGetPreviewHtmlWithInvalidMenu(): void {
		$result = EditorPreview::getPreviewHtml( 999 );
		$this->assertStringContainsString( 'not found', $result );
	}

	public function testEditorPreviewGetPreviewHtmlWithValidMenu(): void {
		$GLOBALS['__nav_menus'][2] = (object) array( 'name' => 'Primary' );
		$result                    = EditorPreview::getPreviewHtml( 2 );
		$this->assertIsString( $result );
	}
}
