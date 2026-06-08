<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Breakdance;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Integrations\AdminNotice;

final class BreakdanceIntegration implements ServiceProvider {
	use AdminNotice;

	public function register(): void {}

	public function boot(): void {
		if ( ! defined( 'BREAKDANCE_MODE' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'registerElement' ) );

		$this->registerNotice(
			'breakdance',
			'<p>' . __( 'To display the iMedia Menu in a Breakdance layout, use the "iMedia Menu Location" element in the Breakdance builder.', 'imedia-menu' )
			. ' <a href="https://breakdance.com/documentation/" target="_blank">' . __( 'Learn more about Breakdance.', 'imedia-menu' ) . '</a></p>'
		);
		$this->enqueueDismissScript();

		add_action( 'admin_enqueue_scripts', array( $this, 'adminStyles' ) );
	}

	public function registerElement(): void {
		if ( ! class_exists( '\Breakdance\Elements\Element' ) ) {
			return;
		}
		\Breakdance\Elements::register_element( Elements\MenuLocation::class );
	}

	public function adminStyles( string $hook ): void {
		if ( ! str_contains( $hook, 'imedia-menu' ) ) {
			return;
		}
		wp_add_inline_style( 'wp-admin', $this->shimmerCss() );
	}

	private function shimmerCss(): string {
		return 'tr.imm-breakdance-option { background: #f0f6fc; animation: imm-shimmer 2s ease-in-out infinite; }
			@keyframes imm-shimmer { 0%, 100% { background: #f0f6fc; } 50% { background: #e5f0fa; } }';
	}
}
