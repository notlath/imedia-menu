<?php

declare(strict_types=1);

namespace IMedia\Menu\Import;

final class MegamenuImporter {

	private MegamenuMenuMapper $menuMapper;
	private MegamenuToggleMapper $toggleMapper;

	public function __construct() {
		$this->menuMapper   = new MegamenuMenuMapper();
		$this->toggleMapper = new MegamenuToggleMapper();
	}

	public function import(): array {
		$results = array(
			'settings'      => false,
			'locations'     => 0,
			'items'         => 0,
			'toggle_blocks' => 0,
			'errors'        => array(),
		);

		$megamenuSettings = get_option( 'megamenu_settings', array() );
		if ( ! is_array( $megamenuSettings ) || $megamenuSettings === array() ) {
			$results['errors'][] = __( 'No megamenu settings found. Is the plugin active?', 'imedia-menu' );
			return $results;
		}

		$mappedSettings = MegamenuMapping::mapSettings( $megamenuSettings );
		update_option( 'imedia_menu_settings', $mappedSettings );
		$results['settings'] = true;

		$locations     = get_nav_menu_locations();
		$locationCount = 0;

		foreach ( $megamenuSettings as $key => $value ) {
			if ( ! is_array( $value ) || ! isset( $value['enabled'] ) ) {
				continue;
			}

			++$locationCount;

			if ( isset( $value['toggle_blocks'] ) && is_array( $value['toggle_blocks'] ) ) {
				$toggleBlocks = $this->toggleMapper->mapToggleBlocks( $value['toggle_blocks'] );
				if ( count( $toggleBlocks ) > 0 ) {
					$toggleBar         = get_option( 'imedia_menu_toggle_bar', array() );
					$toggleBar[ $key ] = $toggleBlocks;
					update_option( 'imedia_menu_toggle_bar', $toggleBar );
					$results['toggle_blocks'] += count( $toggleBlocks );
				}
			}

			$locationOverrides = MegamenuMapping::mergeLocationSettings( $value );
			if ( count( $locationOverrides ) > 0 ) {
				$existing         = get_option( 'imedia_menu_location_overrides', array() );
				$existing[ $key ] = $locationOverrides;
				update_option( 'imedia_menu_location_overrides', $existing );
			}
		}

		$results['locations'] = $locationCount;

		$allMenus = $this->extractMenuItemsFromSettings( $megamenuSettings, $locations );
		foreach ( $allMenus as $menuTermId => $menusData ) {
			$menuResult        = $this->menuMapper->mapItems( $menuTermId, array( $menuTermId => $menusData ) );
			$results['items'] += $menuResult['items'];
			foreach ( $menuResult['errors'] as $error ) {
				$results['errors'][] = $error;
			}
		}

		$themesOption = get_option( 'megamenu_themes', array() );
		if ( is_array( $themesOption ) && count( $themesOption ) > 0 ) {
			$currentSettings = get_option( 'imedia_menu_settings', array() );
			foreach ( $themesOption as $themeId => $themeSettings ) {
				if ( $themeId === 'default' || $themeId === '' ) {
					continue;
				}
				$designOverrides = MegamenuMapping::mapThemeToDesign( $themeSettings );
				if ( count( $designOverrides ) > 0 ) {
					$currentSettings[ 'theme_' . $themeId . '_design' ] = $designOverrides;
				}
			}
			update_option( 'imedia_menu_settings', $currentSettings );
		}

		return $results;
	}

	private function extractMenuItemsFromSettings( array $megamenuSettings, array $locations ): array {
		$menus = array();

		foreach ( $locations as $locationSlug => $menuTermId ) {
			if ( ! $menuTermId ) {
				continue;
			}

			$items = wp_get_nav_menu_items( $menuTermId );
			if ( ! $items || ! is_array( $items ) ) {
				continue;
			}

			$itemData = array();
			foreach ( $items as $item ) {
				$savedSettings = get_post_meta( $item->ID, '_megamenu', true );
				$itemData[]    = array(
					'id'                => $item->ID,
					'megamenu_settings' => is_array( $savedSettings ) ? $savedSettings : array(),
				);
			}

			$menus[ $menuTermId ] = array(
				'items' => $itemData,
			);
		}

		return $menus;
	}
}
