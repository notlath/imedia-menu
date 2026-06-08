<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

final class Sticky {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScript' ), 120 );
	}

	public function enqueueScript(): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$sticky   = $settings['sticky'] ?? false;

		if ( ! $sticky ) {
			return;
		}

		if ( wp_script_is( 'imm-sticky', 'enqueued' ) ) {
			return;
		}

		wp_enqueue_style(
			'imm-sticky',
			IMEDIA_MENU_URL . 'assets/frontend/css/imm-sticky.css',
			array( 'imm-base' ),
			IMEDIA_MENU_VERSION
		);

		wp_enqueue_script(
			'imm-sticky',
			IMEDIA_MENU_URL . 'assets/frontend/js/imm-sticky.js',
			array( 'imm' ),
			IMEDIA_MENU_VERSION,
			true
		);
	}

	public static function attributes( array $settings ): array {
		$attrs = array();

		if ( empty( $settings['sticky'] ) ) {
			return $attrs;
		}

		$attrs['data-sticky-enabled']       = 'true';
		$attrs['data-sticky-desktop']       = ! empty( $settings['sticky_desktop'] ) ? 'true' : 'false';
		$attrs['data-sticky-mobile']        = ! empty( $settings['sticky_mobile'] ) ? 'true' : 'false';
		$attrs['data-sticky-opacity']       = (string) ( $settings['sticky_opacity'] ?? 1.0 );
		$attrs['data-sticky-offset']        = (string) ( $settings['sticky_offset'] ?? 0 );
		$attrs['data-sticky-expand']        = ! empty( $settings['sticky_expand'] ) ? 'true' : 'false';
		$attrs['data-sticky-expand-mobile'] = ! empty( $settings['sticky_expand_mobile'] ) ? 'true' : 'false';

		if ( ! empty( $settings['sticky_hide_until_scroll_up'] ) ) {
			$attrs['data-sticky-hide']           = 'true';
			$attrs['data-sticky-hide-tolerance'] = (string) ( $settings['sticky_hide_until_scroll_up_tolerance'] ?? 10 );
			$attrs['data-sticky-hide-offset']    = (string) ( $settings['sticky_hide_until_scroll_up_offset'] ?? 0 );
		} else {
			$attrs['data-sticky-hide'] = 'false';
		}

		return $attrs;
	}
}
