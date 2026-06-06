<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\MegaPanel;

final class PanelBuilder {

	public function enqueueAssets(): void {
		$script_path = 'assets/admin/panel-builder/build/index.js';
		$style_path  = 'assets/admin/panel-builder/build/index.css';

		if ( ! file_exists( DIR . '/' . $script_path ) ) {
			return;
		}

		$script_asset = require DIR . '/assets/admin/panel-builder/build/index.asset.php';

		wp_enqueue_media();
		wp_enqueue_editor();

		wp_enqueue_script(
			'imedia-menu-panel-builder',
			URL . $script_path,
			$script_asset['dependencies'] ?? array(
				'wp-element',
				'wp-data',
				'wp-components',
				'wp-i18n',
				'wp-api-fetch',
				'wp-block-editor',
			),
			$script_asset['version'] ?? VERSION,
			true
		);

		wp_set_script_translations(
			'imedia-menu-panel-builder',
			'imedia-menu',
			DIR . '/languages'
		);

		$menu_id = 0;
		if ( isset( $_GET['menu'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$menu_id = (int) $_GET['menu']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		wp_localize_script(
			'imedia-menu-panel-builder',
			'imediaPanelBuilder',
			array(
				'restUrl' => rest_url( 'imedia-menu/v1/' ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'menuId'  => $menu_id,
			)
		);

		if ( file_exists( DIR . '/' . $style_path ) ) {
			wp_enqueue_style(
				'imedia-menu-panel-builder',
				URL . $style_path,
				array( 'wp-components' ),
				VERSION
			);
		}
	}

	public function renderModal(): void {
		if ( ! current_user_can( apply_filters( 'imedia_menu_capability', 'edit_theme_options' ) ) ) {
			return;
		}
		?>
		<div id="imedia-panel-builder-modal"
			class="imm-builder-modal"
			aria-hidden="true"
			role="dialog"
			aria-modal="true"
			aria-label="<?php esc_attr_e( 'Mega Panel Builder', 'imedia-menu' ); ?>">
			<div class="imm-builder-modal-backdrop" tabindex="-1"></div>
			<div class="imm-builder-modal-content">
				<div id="imedia-panel-builder-mount"></div>
			</div>
		</div>
		<?php
	}
}
