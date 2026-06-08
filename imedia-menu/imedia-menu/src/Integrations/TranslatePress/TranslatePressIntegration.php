<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\TranslatePress;

use IMedia\Menu\Contracts\ServiceProvider;

final class TranslatePressIntegration implements ServiceProvider {

	public function register(): void {}

	public function boot(): void {
		if ( ! function_exists( 'trp_get_language' ) ) {
			return;
		}

		add_filter( 'imm_cache_key_locale', array( $this, 'cacheKeyLocale' ) );
		add_filter( 'imm_location_assignment_summary', array( $this, 'locationAssignmentSummary' ), 10, 2 );
	}

	public function cacheKeyLocale( string $locale ): string {
		if ( function_exists( 'trp_get_language' ) ) {
			$trpLang = trp_get_language();
			if ( $trpLang ) {
				return $trpLang;
			}
		}
		return $locale;
	}

	public function locationAssignmentSummary( string $summary, string $slug ): string {
		if ( ! function_exists( 'trp_get_languages' ) ) {
			return $summary;
		}
		$languages = trp_get_languages();
		if ( empty( $languages ) ) {
			return $summary;
		}
		$lines     = array();
		$locations = get_nav_menu_locations();
		foreach ( $languages as $code => $label ) {
			$langSlug = $slug . '___' . $code;
			$menuId   = $locations[ $langSlug ] ?? 0;
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
}
