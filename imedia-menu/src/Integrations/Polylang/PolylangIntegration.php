<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Polylang;

use IMedia\Menu\Contracts\ServiceProvider;

final class PolylangIntegration implements ServiceProvider {

	public function register(): void {}

	public function boot(): void {
		if ( ! function_exists( 'pll_default_language' ) ) {
			return;
		}

		add_filter( 'imm_cache_key_locale', array( $this, 'cacheKeyLocale' ) );
		add_filter( 'imm_nav_menu_locations', array( $this, 'normalizeLocations' ) );
		add_filter( 'imm_preview_url_args', array( $this, 'previewUrlArgs' ) );
		add_filter( 'imm_location_assignment_summary', array( $this, 'locationAssignmentSummary' ), 10, 2 );
	}

	public function cacheKeyLocale( string $locale ): string {
		if ( function_exists( 'pll_current_language' ) ) {
			$pllLocale = pll_current_language( 'locale' );
			if ( $pllLocale ) {
				return $pllLocale;
			}
		}
		return $locale;
	}

	public function normalizeLocations( array $locations ): array {
		$cleaned = array();
		foreach ( $locations as $slug => $menuId ) {
			if ( str_contains( $slug, '___' ) ) {
				continue;
			}
			$cleaned[ $slug ] = $menuId;
		}
		return $cleaned;
	}

	public function previewUrlArgs( array $args ): array {
		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language();
			if ( $lang ) {
				$args['lang'] = $lang;
			}
		}
		return $args;
	}

	public function locationAssignmentSummary( string $summary, string $slug ): string {
		if ( ! function_exists( 'pll_languages_list' ) ) {
			return $summary;
		}
		$languages = pll_languages_list();
		if ( empty( $languages ) ) {
			return $summary;
		}
		$lines = array();
		foreach ( $languages as $lang ) {
			$langSlug  = $slug . '___' . $lang;
			$locations = get_nav_menu_locations();
			$menuId    = $locations[ $langSlug ] ?? 0;
			if ( $menuId ) {
				$menu = wp_get_nav_menu_object( $menuId );
				if ( $menu ) {
					$lines[] = '[' . esc_html( $lang ) . '] ' . esc_html( $menu->name );
				}
			}
		}
		if ( empty( $lines ) ) {
			return $summary;
		}
		return implode( ' · ', $lines );
	}
}
