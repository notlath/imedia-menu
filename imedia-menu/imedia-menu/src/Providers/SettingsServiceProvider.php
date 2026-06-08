<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Admin\Settings\PageRegistry;
use IMedia\Menu\Admin\Settings\SettingsPage;
use IMedia\Menu\Admin\Settings\SettingsPageRenderer;
use IMedia\Menu\Admin\Settings\SettingsRegistry;

final class SettingsServiceProvider implements ServiceProvider {

	private SettingsPage $settingsPage;
	private SettingsPageRenderer $renderer;
	private SettingsRegistry $registry;

	public function register(): void {
		$this->registry     = new SettingsRegistry();
		$this->settingsPage = new SettingsPage( $this->registry );
		$this->renderer     = new SettingsPageRenderer( $this->registry );
	}

	public function boot(): void {
		add_action( 'admin_menu', array( $this, 'addSettingsPages' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueSettingsAssets' ) );
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
	}

	public function addSettingsPages(): void {
		$capability = apply_filters( 'imedia_menu_capability', 'edit_theme_options' );

		foreach ( PageRegistry::getAllSlugs() as $slug ) {
			add_submenu_page(
				'themes.php',
				PageRegistry::getPageTitle( $slug ),
				PageRegistry::getMenuLabel( $slug ),
				$capability,
				$slug,
				function () use ( $slug ): void {
					$this->renderer->render( $slug );
				},
				$slug === 'imedia-menu' ? 30 : null
			);
		}
	}

	public function enqueueSettingsAssets( string $hook ): void {
		$pluginPrefix = 'appearance_page_imedia-menu';
		$isOurPage    = false;

		if ( str_starts_with( $hook, $pluginPrefix ) ) {
			$isOurPage = true;
		}

		if ( ! $isOurPage ) {
			return;
		}

		$script_path = 'assets/admin/settings-page/build/index.js';
		$style_path  = 'assets/admin/settings-page/build/index.css';

		if ( ! file_exists( IMEDIA_MENU_DIR . '/' . $script_path ) ) {
			return;
		}

		$script_asset = require IMEDIA_MENU_DIR . '/assets/admin/settings-page/build/index.asset.php';

		wp_enqueue_script(
			'imedia-menu-settings-page',
			IMEDIA_MENU_URL . $script_path,
			$script_asset['dependencies'] ?? array(
				'wp-element',
				'wp-data',
				'wp-components',
				'wp-i18n',
				'wp-api-fetch',
			),
			$script_asset['version'] ?? IMEDIA_MENU_VERSION,
			true
		);

		wp_set_script_translations(
			'imedia-menu-settings-page',
			'imedia-menu',
			IMEDIA_MENU_DIR . '/languages'
		);

		if ( file_exists( IMEDIA_MENU_DIR . '/' . $style_path ) ) {
			wp_enqueue_style(
				'imedia-menu-settings-page',
				IMEDIA_MENU_URL . $style_path,
				array( 'wp-components' ),
				IMEDIA_MENU_VERSION
			);
		}
	}

	public function registerSettings(): void {
		register_setting(
			'imedia_menu_settings',
			'imedia_menu_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitizeSettings' ),
				'default'           => array(),
			)
		);
	}

	public function sanitizeSettings( array $input ): array {
		$sanitized = array();

		foreach ( $this->registry->getAll() as $tab ) {
			$sanitized = array_merge( $sanitized, $tab->sanitize( $input ) );
		}

		return $sanitized;
	}

	public function getSettingsPage(): SettingsPage {
		return $this->settingsPage;
	}

	public function getRegistry(): SettingsRegistry {
		return $this->registry;
	}
}
