<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Import;

use IMedia\Menu\Import\MegamenuImporter;
use IMedia\Menu\Import\MegamenuMapping;
use IMedia\Menu\Import\MegamenuMenuMapper;
use IMedia\Menu\Import\MegamenuToggleMapper;
use PHPUnit\Framework\TestCase;

final class MegamenuImporterTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options'] = array();
		$GLOBALS['_post_meta'] = array();
		$GLOBALS['_nav_menu_locations'] = array();
		$GLOBALS['__nav_menus'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
		unset( $GLOBALS['_post_meta'] );
		unset( $GLOBALS['_nav_menu_locations'] );
		unset( $GLOBALS['__nav_menus'] );
		parent::tearDown();
	}

	// --- MegamenuMapping ---

	public function testMapSettingsReturnsDefaults(): void {
		$result = MegamenuMapping::mapSettings( array() );

		$this->assertArrayHasKey( 'enabled', $result );
		$this->assertTrue( $result['enabled'] );
		$this->assertArrayHasKey( 'mobile_breakpoint', $result );
		$this->assertSame( 768, $result['mobile_breakpoint'] );
		$this->assertArrayHasKey( 'icon_providers', $result );
		$this->assertTrue( $result['icon_providers']['dashicons'] );
	}

	public function testMapSettingsIgnoresMegamenuSpecificKeys(): void {
		$megamenu = array(
			'primary' => array( 'enabled' => true, 'theme' => 'default' ),
			'prefix'  => 'disabled',
		);

		$result = MegamenuMapping::mapSettings( $megamenu );

		$this->assertTrue( $result['enabled'] );
	}

	public function testMergeLocationSettingsSticky(): void {
		$settings = array(
			'sticky_enabled' => true,
			'sticky_offset'  => '100px',
		);

		$result = MegamenuMapping::mergeLocationSettings( $settings );

		$this->assertTrue( $result['sticky'] );
		$this->assertSame( 100, $result['sticky_offset'] );
	}

	public function testMergeLocationSettingsNoSticky(): void {
		$result = MegamenuMapping::mergeLocationSettings( array() );
		$this->assertSame( array(), $result );
	}

	public function testMapMenuItemMetaMegamenu(): void {
		$megameta = array(
			'type'           => 'megamenu',
			'icon'           => 'dashicons-admin-home',
			'hide_on_mobile' => true,
			'disable_link'   => false,
			'hide_arrow'     => true,
			'hide_text'      => false,
			'description'    => 'Test description',
		);

		$result = MegamenuMapping::mapMenuItemMeta( $megameta );

		$this->assertSame( '1', $result['_imedia_menu_mega_enabled'] );
		$this->assertSame( 'dashicons-admin-home', $result['_imedia_menu_icon'] );
		$this->assertSame( '1', $result['_imedia_menu_hide_on_mobile'] );
		$this->assertSame( '0', $result['_imedia_menu_disable_link'] );
		$this->assertSame( '1', $result['_imedia_menu_hide_arrow'] );
		$this->assertSame( '0', $result['_imedia_menu_hide_text'] );
		$this->assertSame( 'Test description', $result['_imedia_menu_description'] );
	}

	public function testMapMenuItemMetaFlyout(): void {
		$result = MegamenuMapping::mapMenuItemMeta( array( 'type' => 'flyout' ) );
		$this->assertSame( '0', $result['_imedia_menu_mega_enabled'] );
	}

	public function testMapMenuItemMetaBadge(): void {
		$megameta = array(
			'badge' => array(
				'text'        => 'Sale',
				'background'  => '#ff0000',
				'text_color'  => '#ffffff',
				'style'       => 'style-1',
			),
		);

		$result = MegamenuMapping::mapMenuItemMeta( $megameta );

		$this->assertSame( 'Sale', $result['_imedia_menu_badge_text'] );
		$this->assertSame( '#ff0000', $result['_imedia_menu_badge_color'] );
		$this->assertSame( '#ffffff', $result['_imedia_menu_badge_text_color'] );
		$this->assertSame( 'style-1', $result['_imedia_menu_badge_position'] );
	}

	public function testMapMenuItemMetaRolesLoggedIn(): void {
		$megameta = array(
			'roles' => array(
				'display_mode' => 'logged_in',
				'roles'        => array( 'administrator', 'editor' ),
			),
		);

		$result = MegamenuMapping::mapMenuItemMeta( $megameta );
		$visibility = json_decode( $result['_imedia_menu_visibility'], true );

		$this->assertSame( 'login_state', $visibility['type'] );
		$this->assertSame( 'in', $visibility['state'] );
	}

	public function testMapMenuItemMetaRolesLoggedOut(): void {
		$megameta = array(
			'roles' => array(
				'display_mode' => 'logged_out',
				'roles'        => array(),
			),
		);

		$result = MegamenuMapping::mapMenuItemMeta( $megameta );
		$visibility = json_decode( $result['_imedia_menu_visibility'], true );

		$this->assertSame( 'login_state', $visibility['type'] );
		$this->assertSame( 'out', $visibility['state'] );
	}

	public function testMapMenuItemMetaNoRoles(): void {
		$result = MegamenuMapping::mapMenuItemMeta( array() );
		$this->assertArrayNotHasKey( '_imedia_menu_visibility', $result );
	}

	public function testParseIconClassDashicons(): void {
		$result = MegamenuMapping::parseIconClass( 'dashicons-admin-home' );
		$this->assertSame( 'dashicons', $result['provider'] );
		$this->assertSame( 'dashicons-admin-home', $result['icon'] );
	}

	public function testParseIconClassFontAwesome(): void {
		$result = MegamenuMapping::parseIconClass( 'fab fa-facebook' );
		$this->assertSame( 'fontawesome', $result['provider'] );
	}

	public function testParseIconClassEmpty(): void {
		$result = MegamenuMapping::parseIconClass( '' );
		$this->assertSame( '', $result['provider'] );
		$this->assertSame( '', $result['icon'] );
	}

	public function testMapThemeToDesignMapsKnownKeys(): void {
		$theme = array(
			'arrow_color'             => '#333333',
			'panel_background_color'  => '#ffffff',
		);

		$result = MegamenuMapping::mapThemeToDesign( $theme );

		$this->assertSame( '#333333', $result['menu_text_color'] );
		$this->assertSame( '#ffffff', $result['dropdown_bg'] );
		$this->assertArrayNotHasKey( 'unknown_custom_key', $result );
	}

	public function testMapThemeToDesignEmpty(): void {
		$result = MegamenuMapping::mapThemeToDesign( array() );
		$this->assertSame( array(), $result );
	}

	// --- MegamenuMenuMapper ---

	public function testMapItemsWritesPostMeta(): void {
		$GLOBALS['_post_meta'][5] = array();

		$menusData = array(
			10 => array(
				'items' => array(
					array(
						'id' => 5,
						'megamenu_settings' => array(
							'type'           => 'megamenu',
							'icon'           => 'dashicons-admin-home',
							'hide_on_mobile' => true,
						),
					),
				),
			),
		);

		$mapper = new MegamenuMenuMapper();
		$result = $mapper->mapItems( 10, $menusData );

		$this->assertSame( 1, $result['items'] );
		$this->assertSame( '1', $GLOBALS['_post_meta'][5]['_imedia_menu_mega_enabled'] );
		$this->assertSame( 'dashicons-admin-home', $GLOBALS['_post_meta'][5]['_imedia_menu_icon'] );
		$this->assertSame( '1', $GLOBALS['_post_meta'][5]['_imedia_menu_hide_on_mobile'] );
	}

	public function testMapItemsSkipsMissingItemId(): void {
		$menusData = array(
			10 => array(
				'items' => array(
					array( 'id' => 0, 'megamenu_settings' => array() ),
				),
			),
		);

		$mapper = new MegamenuMenuMapper();
		$result = $mapper->mapItems( 10, $menusData );

		$this->assertSame( 0, $result['items'] );
	}

	public function testMapItemsHandlesMissingMenu(): void {
		$mapper = new MegamenuMenuMapper();
		$result = $mapper->mapItems( 999, array() );

		$this->assertSame( 0, $result['items'] );
	}

	// --- MegamenuToggleMapper ---

	public function testMapToggleBlocksAllTypes(): void {
		$blocks = array(
			array( 'type' => 'menu_toggle', 'order' => 1 ),
			array( 'type' => 'menu_toggle_animated', 'order' => 2 ),
			array( 'type' => 'spacer', 'order' => 3 ),
			array( 'type' => 'logo', 'order' => 4, 'logo_id' => 10, 'logo_link' => 'https://example.com' ),
			array( 'type' => 'search', 'order' => 5, 'search_type' => 'icon' ),
			array( 'type' => 'html', 'order' => 6, 'html' => '<p>Hello</p>' ),
			array( 'type' => 'social', 'order' => 7, 'social_links' => array( array( 'url' => 'https://x.com' ) ) ),
			array( 'type' => 'menu_toggle_custom', 'order' => 8, 'label' => 'Custom' ),
		);

		$mapper   = new MegamenuToggleMapper();
		$result = $mapper->mapToggleBlocks( $blocks );

		$this->assertCount( 8, $result );
		$this->assertSame( 'menu_toggle', $result[0]['type'] );
		$this->assertSame( 'logo', $result[3]['type'] );
		$this->assertSame( 10, $result[3]['logo_id'] );
		$this->assertSame( 'custom', $result[7]['type'] );
		$this->assertSame( 'Custom', $result[7]['label'] );
	}

	public function testMapToggleBlocksSkipsUnknownType(): void {
		$blocks = array(
			array( 'type' => 'nonexistent', 'order' => 1 ),
		);

		$mapper   = new MegamenuToggleMapper();
		$result = $mapper->mapToggleBlocks( $blocks );

		$this->assertCount( 0, $result );
	}

	public function testMapToggleBlocksSortsByOrder(): void {
		$blocks = array(
			array( 'type' => 'spacer', 'order' => 5 ),
			array( 'type' => 'menu_toggle', 'order' => 1 ),
			array( 'type' => 'search', 'order' => 3 ),
		);

		$mapper   = new MegamenuToggleMapper();
		$result = $mapper->mapToggleBlocks( $blocks );

		$this->assertCount( 3, $result );
		$this->assertSame( 'menu_toggle', $result[0]['type'] );
		$this->assertSame( 'search', $result[1]['type'] );
		$this->assertSame( 'spacer', $result[2]['type'] );
	}

	// --- MegamenuImporter ---

	public function testImportReturnsErrorsWhenNoMegamenuSettings(): void {
		$importer = new MegamenuImporter();
		$result   = $importer->import();

		$this->assertNotEmpty( $result['errors'] );
		$this->assertFalse( $result['settings'] );
	}

	public function testImportWithMegamenuSettings(): void {
		update_option( 'megamenu_settings', array(
			'primary' => array(
				'enabled'    => true,
				'theme'      => 'default',
				'toggle_blocks' => array(
					array( 'type' => 'menu_toggle', 'order' => 1 ),
				),
			),
		) );

		$importer = new MegamenuImporter();
		$result   = $importer->import();

		$this->assertEmpty( $result['errors'] );
		$this->assertTrue( $result['settings'] );
		$this->assertSame( 1, $result['locations'] );
		$this->assertSame( 1, $result['toggle_blocks'] );
	}

	public function testImportWithMegamenuThemes(): void {
		$GLOBALS['_imedia_menu_options']['megamenu_settings'] = array(
			'primary' => array( 'enabled' => true ),
		);
		$GLOBALS['_imedia_menu_options']['megamenu_themes'] = array(
			'custom_theme_1' => array(
				'arrow_color'            => '#333333',
				'panel_background_color' => '#ffffff',
			),
		);

		$importer = new MegamenuImporter();
		$result   = $importer->import();

		$this->assertEmpty( $result['errors'] );
		$this->assertTrue( $result['settings'] );

		$saved = get_option( 'imedia_menu_settings' );
		$this->assertIsArray( $saved );
	}

	public function testImportWithMenuItems(): void {
		$menuItemId = 100;

		$GLOBALS['_imedia_menu_options']['megamenu_settings'] = array(
			'primary' => array( 'enabled' => true ),
		);
		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5] = (object) array( 'term_id' => 5, 'name' => 'Test Menu' );
		$GLOBALS['_post_meta'][ $menuItemId ] = array();
		$GLOBALS['_post_meta'][ $menuItemId ]['_megamenu'] = array(
			'type'           => 'megamenu',
			'icon'           => 'dashicons-admin-home',
			'hide_on_mobile' => true,
		);

		$importer = new MegamenuImporter();
		$result   = $importer->import();

		$this->assertEmpty( $result['errors'] );
	}

	public function testImportMenuItemsWritesMeta(): void {
		$menuItemId = 101;

		$GLOBALS['_imedia_menu_options']['megamenu_settings'] = array(
			'primary' => array( 'enabled' => true ),
		);
		$GLOBALS['_nav_menu_locations'] = array( 'primary' => 5 );
		$GLOBALS['__nav_menus'][5] = (object) array( 'term_id' => 5, 'name' => 'Test Menu' );
		$GLOBALS['_post_meta'][ $menuItemId ] = array();
		$GLOBALS['_post_meta'][ $menuItemId ]['_megamenu'] = array(
			'type' => 'megamenu',
			'icon' => 'dashicons-admin-site',
		);

		$importer = new MegamenuImporter();
		$result   = $importer->import();

		$this->assertSame( $result['items'], 0 );
		$this->assertEmpty( $result['errors'] );
	}
}
