<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations;

trait AdminNotice {

	public function registerNotice( string $key, string $noticeHtml, string $capability = 'edit_theme_options' ): void {
		add_action(
			'admin_notices',
			function () use ( $key, $noticeHtml, $capability ): void {
				if ( ! current_user_can( $capability ) ) {
					return;
				}
				if ( get_user_meta( get_current_user_id(), 'imm_dismissed_' . $key, true ) ) {
					return;
				}
				?>
			<div class="notice notice-info imm-admin-notice is-dismissible" data-imm-notice="<?php echo esc_attr( $key ); ?>">
				<?php echo wp_kses_post( $noticeHtml ); ?>
			</div>
				<?php
			}
		);

		add_action(
			'wp_ajax_imm_dismiss_admin_notice',
			function () use ( $key, $capability ): void {
				if ( ! current_user_can( $capability ) ) {
					wp_die( -1 );
				}
				check_ajax_referer( 'imm_dismiss', 'nonce' );
				$dismissKey = sanitize_key( wp_unslash( $_POST['key'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				if ( $dismissKey === $key ) {
					update_user_meta( get_current_user_id(), 'imm_dismissed_' . $key, true );
				}
				wp_die( 1 );
			}
		);
	}

	public function enqueueDismissScript(): void {
		add_action(
			'admin_enqueue_scripts',
			function (): void {
				$nonce = wp_create_nonce( 'imm_dismiss' );
				ob_start();
				?>
			( function() {
				window.immDismissNonce = <?php echo wp_json_encode( $nonce ); ?>;
				document.addEventListener( 'click', function( e ) {
					const btn = e.target.closest( '.notice.is-dismissible[data-imm-notice] .notice-dismiss' );
					if ( ! btn ) return;
					const notice = btn.closest( '[data-imm-notice]' );
					if ( ! notice ) return;
					const key = notice.getAttribute( 'data-imm-notice' );
					if ( ! key ) return;
					const data = new URLSearchParams();
					data.set( 'action', 'imm_dismiss_admin_notice' );
					data.set( 'key', key );
					data.set( 'nonce', window.immDismissNonce || '' );
					navigator.sendBeacon ? navigator.sendBeacon( ajaxurl, data ) : fetch( ajaxurl, { method: 'POST', body: data } );
				} );
			} )();
				<?php
				$script = ob_get_clean();
				wp_add_inline_script( 'common', $script );
			}
		);
	}
}
