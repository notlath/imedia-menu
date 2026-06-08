<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Enums\MenuOrientation;
use IMedia\Menu\Enums\OverlayMode;

final class Overlay {

	public function register(): void {
		add_action( 'wp_footer', array( $this, 'render' ), 20 );
	}

	public function render(): void {
		$registered = get_registered_nav_menus();

		if ( empty( $registered ) ) {
			return;
		}

		$locations = get_nav_menu_locations();
		$emitted   = false;
		$overlay   = '';

		foreach ( array_keys( $registered ) as $slug ) {
			if ( empty( $locations[ $slug ] ) ) {
				continue;
			}

			$global = get_option( 'imedia_menu_settings', array() );
			$merged = LocationOverrides::mergeWithGlobal( $global, $slug );
			$mode   = OverlayMode::fromStringOrDefault( $merged['overlay'] ?? 'off' );

			if ( $mode === OverlayMode::Off ) {
				continue;
			}

			$color    = (string) ( $merged['overlay_color'] ?? 'rgba(0,0,0,0.3)' );
			$emitted  = true;
			$overlay .= sprintf(
				'<div class="imm-page-overlay" data-imm-overlay="%s" data-imm-overlay-locations="%s" hidden style="--imm-overlay-color:%s"></div>',
				esc_attr( $mode->value ),
				esc_attr( $slug ),
				esc_attr( $color )
			);
		}

		if ( ! $emitted ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is built with esc_attr above.
		echo $overlay;
		$this->printScript();
	}

	private function printScript(): void {
		?>
		<script id="imm-overlay-watcher">
		(function(){
			if ( window.__immOverlayInit ) { return; }
			window.__immOverlayInit = true;
			var overlays = document.querySelectorAll('.imm-page-overlay');
			if ( !overlays.length ) { return; }
			var mode = overlays[0].getAttribute('data-imm-overlay');
			var showsOn = (mode === 'desktop') ? 'min-width:769px'
				: (mode === 'mobile') ? 'max-width:768px'
				: 'all';
			function context() {
				if ( showsOn === 'all' ) { return true; }
				return showsOn === 'min-width:769px'
					? window.matchMedia('(min-width:769px)').matches
					: window.matchMedia('(max-width:768px)').matches;
			}
			function isOpen() {
				return document.querySelector('.imm-nav .imm-link[aria-expanded="true"]') !== null;
			}
			function sync() {
				var open = isOpen() && context();
				overlays.forEach(function(el){
					if ( open ) { el.removeAttribute('hidden'); }
					else { el.setAttribute('hidden',''); }
				});
				document.documentElement.classList.toggle('imm-has-overlay', open);
			}
			document.addEventListener('click', function(){ setTimeout(sync, 0); }, true);
			document.addEventListener('focusin', sync);
			document.addEventListener('mouseover', sync);
			window.addEventListener('resize', sync);
			overlays.forEach(function(el){
				el.addEventListener('click', function(){
					document.querySelectorAll('.imm-nav .imm-link[aria-expanded="true"]').forEach(function(a){
						a.setAttribute('aria-expanded', 'false');
					});
					sync();
				});
			});
			sync();
		})();
		</script>
		<?php
	}
}
