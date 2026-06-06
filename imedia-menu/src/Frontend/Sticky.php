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

		wp_enqueue_script(
			'imm-sticky',
			IMEDIA_MENU_URL . 'assets/frontend/js/imm-sticky.js',
			array( 'imm' ),
			IMEDIA_MENU_VERSION,
			true
		);
	}
}
