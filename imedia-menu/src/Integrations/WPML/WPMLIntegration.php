<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\WPML;

use IMedia\Menu\Contracts\ServiceProvider;

final class WPMLIntegration implements ServiceProvider {

	public function register(): void {}

	public function boot(): void {
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return;
		}

		add_filter( 'imm_cache_key_locale', array( $this, 'cacheKeyLocale' ) );
		add_filter( 'imm_location_assignment_summary', array( $this, 'locationAssignmentSummary' ), 10, 2 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'addLanguageSwitcherClass' ), 10, 2 );
	}

	public function cacheKeyLocale( string $locale ): string {
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			return ICL_LANGUAGE_CODE;
		}
		return $locale;
	}

	public function locationAssignmentSummary( string $summary, string $slug ): string {
		$activeLangs = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
		if ( empty( $activeLangs ) ) {
			return $summary;
		}
		$lines = array();
		foreach ( $activeLangs as $code => $lang ) {
			$menuId = apply_filters( 'wpml_object_id', 0, 'nav_menu', false, $code );
			if ( $menuId ) {
				$menu = wp_get_nav_menu_object( $menuId );
				if ( $menu ) {
					$lines[] = '[' . esc_html( $code ) . '] ' . esc_html( $menu->name );
				}
			}
		}
		if ( empty( $lines ) ) {
			return $summary;
		}
		return implode( ' · ', $lines );
	}

	public function addLanguageSwitcherClass( array $items, \stdClass $args ): array {
		foreach ( $items as $item ) {
			if ( in_array( 'wpml-ls-item', $item->classes, true ) ) {
				$item->classes[] = 'menu-flyout';
			}
		}
		return $items;
	}
}
