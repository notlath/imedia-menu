<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Admin\Assets as AdminAssets;

final class AdminServiceProvider implements ServiceProvider {

	private AdminAssets $assets;

	public function register(): void {
		$this->assets = new AdminAssets();
	}

	public function boot(): void {
		add_action( 'admin_enqueue_scripts', array( $this->assets, 'enqueue' ) );
		add_action( 'admin_bar_menu', array( $this, 'addAdminBarLink' ), 100 );
	}

	public function addAdminBarLink( \WP_Admin_Bar $adminBar ): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$showLink = $settings['admin_bar_preview'] ?? true;

		if ( ! $showLink ) {
			return;
		}

		$adminBar->add_node(
			array(
				'id'     => 'imedia-menu-preview',
				'title'  => __( 'iMedia Menu', 'imedia-menu' ),
				'href'   => admin_url( 'themes.php?page=imedia-menu' ),
				'parent' => 'appearance',
			)
		);
	}
}
