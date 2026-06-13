<?php

declare(strict_types=1);

namespace IMedia\Menu\Fonts;

final class FontsManager {

	private const SETTINGS_KEY = 'imedia_menu_settings';

	public function enqueue(): void {
		$settings = get_option( self::SETTINGS_KEY, array() );
		$fonts    = $settings['google_fonts'] ?? array();

		if ( ! empty( $fonts ) && is_array( $fonts ) ) {
			$url = GoogleFontsProvider::getFontUrl( $fonts );

			if ( $url !== '' ) {
				wp_enqueue_style(
					'imm-google-fonts',
					$url,
					array(),
					'1.5.0'
				);
			}
		}

		// Apply the selected menu font family and size via CSS custom properties.
		$fontFamily = $settings['font_family'] ?? '';
		$fontSize   = $settings['font_size'] ?? 0;

		// Auto-use first enabled Google Font when font_family is not explicitly set.
		if ( $fontFamily === '' && ! empty( $fonts ) && is_array( $fonts ) ) {
			$fontNames = array_keys( $fonts );
			$fontFamily = $fontNames[0] ?? '';
		}

		if ( $fontFamily !== '' || $fontSize > 0 ) {
			$css = '.imm-nav {';
			if ( $fontFamily !== '' ) {
				$css .= '--imm-font-family:\'' . str_replace( "'", "\\'", $fontFamily ) . '\',sans-serif;';
			}
			if ( $fontSize > 0 ) {
				$css .= '--imm-font-size:' . (int) $fontSize . 'px;';
			}
			$css .= '}';

			wp_add_inline_style( 'imm-base', $css );
		}
	}

	public function getEnabledFonts(): array {
		$settings = get_option( self::SETTINGS_KEY, array() );
		$fonts    = $settings['google_fonts'] ?? array();

		if ( ! is_array( $fonts ) ) {
			return array();
		}

		return $fonts;
	}

	public function saveFonts( array $fonts ): void {
		$settings                 = get_option( self::SETTINGS_KEY, array() );
		$settings['google_fonts'] = $fonts;
		update_option( self::SETTINGS_KEY, $settings );
	}
}
