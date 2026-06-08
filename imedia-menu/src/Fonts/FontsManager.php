<?php

declare(strict_types=1);

namespace IMedia\Menu\Fonts;

final class FontsManager {

	private const SETTINGS_KEY = 'imedia_menu_settings';

	public function enqueue(): void {
		$settings = get_option( self::SETTINGS_KEY, array() );
		$fonts    = $settings['google_fonts'] ?? array();

		if ( empty( $fonts ) || ! is_array( $fonts ) ) {
			return;
		}

		$url = GoogleFontsProvider::getFontUrl( $fonts );

		if ( $url === '' ) {
			return;
		}

		wp_enqueue_style(
			'imm-google-fonts',
			$url,
			array(),
			'1.5.0'
		);
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
