<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Admin\MegaPanel\PanelBuilder;
use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Enums\BlockType;

final class MegaPanelServiceProvider implements ServiceProvider {

	private ?PanelBuilder $panelBuilder = null;

	public function register(): void {
		$this->panelBuilder = new PanelBuilder();
	}

	public function boot(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueBuilderAssets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueBuilderOnMenuEditor' ) );
		add_action( 'admin_footer-nav-menus.php', array( $this, 'renderBuilderModal' ) );
	}

	public function enqueueBuilderAssets( string $hook ): void {
		if ( $hook !== 'appearance_page_imedia-menu' ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen && isset( $_GET['tab'] ) && $_GET['tab'] !== 'builder' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		wp_enqueue_media();
		wp_enqueue_editor();

		$script_path = 'assets/admin/panel-builder/build/index.js';
		$style_path  = 'assets/admin/panel-builder/build/index.css';

		if ( file_exists( IMEDIA_MENU_DIR . '/' . $script_path ) ) {
			$script_asset = require IMEDIA_MENU_DIR . '/assets/admin/panel-builder/build/index.asset.php';

			wp_enqueue_script(
				'imedia-menu-panel-builder',
				IMEDIA_MENU_URL . $script_path,
				$script_asset['dependencies'] ?? array(
					'wp-element',
					'wp-data',
					'wp-components',
					'wp-i18n',
					'wp-api-fetch',
					'wp-block-editor',
				),
				$script_asset['version'] ?? IMEDIA_MENU_VERSION,
				true
			);

			wp_set_script_translations(
				'imedia-menu-panel-builder',
				'imedia-menu',
				IMEDIA_MENU_DIR . '/languages'
			);

			wp_localize_script(
				'imedia-menu-panel-builder',
				'imediaPanelBuilder',
				array(
					'restUrl' => rest_url( 'imedia-menu/v1/' ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'itemId'  => isset( $_GET['item_id'] ) ? (int) $_GET['item_id'] : 0, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				)
			);
		}

		if ( file_exists( IMEDIA_MENU_DIR . '/' . $style_path ) ) {
			wp_enqueue_style(
				'imedia-menu-panel-builder',
				IMEDIA_MENU_URL . $style_path,
				array( 'wp-components' ),
				IMEDIA_MENU_VERSION
			);
		}
	}

	public function enqueueBuilderOnMenuEditor( string $hook ): void {
		if ( $hook !== 'nav-menus.php' ) {
			return;
		}

		if ( $this->panelBuilder === null ) {
			$this->panelBuilder = new PanelBuilder();
		}

		$this->panelBuilder->enqueueAssets();

		wp_enqueue_style(
			'imedia-menu-admin',
			IMEDIA_MENU_URL . 'assets/admin/css/imedia-admin.css',
			array( 'wp-components', 'imedia-menu-panel-builder' ),
			IMEDIA_MENU_VERSION
		);
	}

	public function renderBuilderModal(): void {
		if ( $this->panelBuilder === null ) {
			return;
		}

		$this->panelBuilder->renderModal();
	}
}
