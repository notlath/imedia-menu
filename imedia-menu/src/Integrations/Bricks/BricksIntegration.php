<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Bricks;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Integrations\AdminNotice;

final class BricksIntegration implements ServiceProvider {
	use AdminNotice;

	public function register(): void {}

	public function boot(): void {
		if ( get_template() !== 'bricks' ) {
			return;
		}

		add_action( 'init', array( $this, 'registerElement' ) );
		$this->registerNotice(
			'bricks',
			'<p>' . __( 'To display the iMedia Menu in a Bricks layout, use the "iMedia Menu Location" element in the Bricks editor.', 'imedia-menu' )
			. ' <a href="https://bricksbuilder.io/doc/templates/" target="_blank">' . __( 'Learn more about Bricks templates.', 'imedia-menu' ) . '</a></p>'
		);
		$this->enqueueDismissScript();

		add_action( 'admin_enqueue_scripts', array( $this, 'adminStyles' ) );
	}

	public function registerElement(): void {
		if ( ! class_exists( '\Bricks\Elements' ) ) {
			return;
		}
		\Bricks\Elements::register_element( Elements\MenuLocation::class );
	}

	public function adminStyles( string $hook ): void {
		if ( ! str_contains( $hook, 'imedia-menu' ) ) {
			return;
		}
		wp_add_inline_style( 'wp-admin', $this->shimmerCss() );
	}

	private function shimmerCss(): string {
		return 'tr.imm-bricks-option { background: #f0f6fc; animation: imm-shimmer 2s ease-in-out infinite; }
			@keyframes imm-shimmer { 0%, 100% { background: #f0f6fc; } 50% { background: #e5f0fa; } }';
	}
}
