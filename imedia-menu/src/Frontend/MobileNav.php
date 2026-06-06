<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

final class MobileNav {

	public function renderOffCanvas(): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$enabled  = $settings['enabled'] ?? true;

		if ( ! $enabled ) {
			return;
		}

		?>
		<div class="imm-overlay" aria-hidden="true"></div>
		<div class="imm-mobile-nav" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation Menu', 'imedia-menu' ); ?>">
			<button class="imm-mobile-close" aria-label="<?php esc_attr_e( 'Close navigation menu', 'imedia-menu' ); ?>">
				<span class="dashicons dashicons-no" aria-hidden="true"></span>
			</button>
			<nav class="imm-mobile-content" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'imedia-menu' ); ?>"></nav>
		</div>
		<?php
	}
}
