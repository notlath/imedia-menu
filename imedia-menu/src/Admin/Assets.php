<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin;

final class Assets {

	public function enqueue( string $hook ): void {
		if ( $hook !== 'appearance_page_imedia-menu' && $hook !== 'nav-menus.php' ) {
			return;
		}

		wp_enqueue_style(
			'imedia-menu-admin',
			IMEDIA_MENU_URL . 'assets/admin/css/imedia-admin.css',
			array( 'wp-components' ),
			IMEDIA_MENU_VERSION
		);

		if ( $hook === 'nav-menus.php' ) {
			wp_enqueue_script(
				'imedia-menu-menu-editor',
				IMEDIA_MENU_URL . 'assets/admin/js/imedia-menu-editor.js',
				array( 'jquery' ),
				IMEDIA_MENU_VERSION,
				true
			);

			wp_localize_script(
				'imedia-menu-menu-editor',
				'imediaMenuEditor',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'imedia_menu_editor' ),
					'strings' => array(
						'openBuilder' => __( 'Open Mega Panel Builder', 'imedia-menu' ),
					),
				)
			);
		}
	}
}
