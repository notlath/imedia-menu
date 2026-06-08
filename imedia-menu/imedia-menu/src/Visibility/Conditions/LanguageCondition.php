<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class LanguageCondition implements VisibilityCondition {

	public function type(): string {
		return 'language';
	}

	public function label(): string {
		return __( 'Language/Locale', 'imedia-menu' );
	}

	public function evaluate( array $config ): bool {
		$allowedLocales = $config['locales'] ?? array();

		if ( empty( $allowedLocales ) ) {
			return true;
		}

		$currentLocale = $this->detectLocale();

		return in_array( $currentLocale, $allowedLocales, true );
	}

	private function detectLocale(): string {
		$settings = get_option( 'imedia_menu_settings', array() );
		$method   = $settings['locale_detection_method'] ?? 'auto';

		switch ( $method ) {
			case 'wpml':
				return $this->detectWpml();
			case 'polylang':
				return $this->detectPolylang();
			case 'translatepress':
				return $this->detectTranslatePress();
			default:
				return $this->detectAuto();
		}
	}

	private function detectAuto(): string {
		if ( function_exists( 'pll_current_language' ) ) {
			$locale = pll_current_language( 'locale' );
			if ( $locale ) {
				return $locale;
			}
		}

		if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'wpml_get_language_information' ) ) {
			$info = wpml_get_language_information( null );
			if ( is_array( $info ) && isset( $info['locale'] ) ) {
				return $info['locale'];
			}
		}

		if ( function_exists( 'trp_get_language' ) ) {
			$lang = trp_get_language();
			if ( $lang ) {
				return $lang;
			}
		}

		return get_locale();
	}

	private function detectPolylang(): string {
		if ( function_exists( 'pll_current_language' ) ) {
			$locale = pll_current_language( 'locale' );
			if ( $locale ) {
				return $locale;
			}
		}
		return get_locale();
	}

	private function detectWpml(): string {
		if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'wpml_get_language_information' ) ) {
			$info = wpml_get_language_information( null );
			if ( is_array( $info ) && isset( $info['locale'] ) ) {
				return $info['locale'];
			}
		}
		return get_locale();
	}

	private function detectTranslatePress(): string {
		if ( function_exists( 'trp_get_language' ) ) {
			$lang = trp_get_language();
			if ( $lang ) {
				return $lang;
			}
		}
		return get_locale();
	}
}
