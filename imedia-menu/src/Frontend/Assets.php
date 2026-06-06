<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

final class Assets {

	public function enqueue(): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$enabled  = $settings['enabled'] ?? true;

		if ( ! $enabled ) {
			return;
		}

		wp_enqueue_style(
			'imm-base',
			IMEDIA_MENU_URL . 'assets/frontend/css/imm-base.css',
			array(),
			IMEDIA_MENU_VERSION
		);

		$this->enqueueCustomCss();

		wp_enqueue_script(
			'imm',
			IMEDIA_MENU_URL . 'assets/frontend/js/imm.js',
			array(),
			IMEDIA_MENU_VERSION,
			true
		);

		wp_localize_script(
			'imm',
			'immData',
			array(
				'trigger'    => $settings['trigger_type'] ?? 'hover',
				'hoverDelay' => (int) ( $settings['hover_delay'] ?? 200 ),
				'animation'  => $settings['default_animation'] ?? 'fade',
				'duration'   => (int) ( $settings['animation_duration'] ?? 200 ),
				'breakpoint' => (int) ( $settings['mobile_breakpoint'] ?? 768 ),
			)
		);

		$this->enqueueConditionalAssets();
	}

	public function enqueueCustomCss(): void {
		$uploadDir = wp_upload_dir();
		$customCss = $uploadDir['basedir'] . '/imedia-menu/imm-custom.css';

		if ( file_exists( $customCss ) ) {
			wp_enqueue_style(
				'imm-custom',
				$uploadDir['baseurl'] . '/imedia-menu/imm-custom.css',
				array( 'imm-base' ),
				filemtime( $customCss )
			);
		}
	}

	public function enqueueConditionalAssets(): void {
		if ( has_filter( 'wp_nav_menu_items', '__return_true' ) === false ) {
			return;
		}
	}

	public function generateCustomCss( array $settings ): bool {
		$uploadDir = wp_upload_dir();
		$cssDir    = $uploadDir['basedir'] . '/imedia-menu';

		if ( ! is_dir( $cssDir ) ) {
			wp_mkdir_p( $cssDir );
		}

		$vars  = ':root {' . "\n";
		$vars .= "  --imm-font-family: {$settings['font_family']};\n";
		$vars .= "  --imm-font-size: {$settings['font_size']}px;\n";
		$vars .= "  --imm-font-weight: {$settings['font_weight']};\n";
		$vars .= "  --imm-line-height: {$settings['line_height']};\n";
		$vars .= "  --imm-height: {$settings['menu_bar_height']}px;\n";
		$vars .= "  --imm-item-padding: {$settings['item_padding']};\n";
		$vars .= "  --imm-panel-padding: {$settings['panel_padding']};\n";
		$vars .= "  --imm-gap: {$settings['item_gap']}px;\n";
		$vars .= "  --imm-duration: {$settings['animation_duration']}ms;\n";
		$vars .= "  --imm-z-index: {$settings['z_index']};\n";
		$vars .= "}\n";

		$css = file_put_contents( $cssDir . '/imm-custom.css', $vars ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

		return $css !== false;
	}

	public function maybeInlineCustomCss(): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		if ( empty( $settings ) ) {
			return;
		}

		$css = $this->buildInlineCss( $settings );
		if ( $css ) {
			wp_add_inline_style( 'imm-base', $css );
		}
	}

	private function buildInlineCss( array $settings ): string {
		$rules   = array();
		$navRule = '';

		if ( ! empty( $settings['menu_bar_bg'] ) ) {
			$navRule .= "  --imm-bg: {$settings['menu_bar_bg']};\n";
		}

		if ( ! empty( $settings['menu_text_color'] ) ) {
			$navRule .= "  --imm-text: {$settings['menu_text_color']};\n";
		}

		if ( ! empty( $settings['menu_text_hover'] ) ) {
			$navRule .= "  --imm-text-hover: {$settings['menu_text_hover']};\n";
		}

		if ( ! empty( $settings['dropdown_bg'] ) ) {
			$navRule .= "  --imm-dropdown-bg: {$settings['dropdown_bg']};\n";
		}

		if ( $navRule !== '' ) {
			$rules[] = ".imm-nav {\n{$navRule}}";
		}

		if ( ! empty( $settings['dark_mode_enabled'] ) ) {
			$darkRule = '';

			if ( ! empty( $settings['dark_mode_bg'] ) ) {
				$darkRule .= "  --imm-bg: {$settings['dark_mode_bg']};\n";
			}

			if ( ! empty( $settings['dark_mode_text'] ) ) {
				$darkRule .= "  --imm-text: {$settings['dark_mode_text']};\n";
			}

			if ( ! empty( $settings['dark_mode_text_hover'] ) ) {
				$darkRule .= "  --imm-text-hover: {$settings['dark_mode_text_hover']};\n";
			}

			if ( ! empty( $settings['dark_mode_dropdown_bg'] ) ) {
				$darkRule .= "  --imm-dropdown-bg: {$settings['dark_mode_dropdown_bg']};\n";
			}

			if ( ! empty( $settings['dark_mode_dropdown_border'] ) ) {
				$darkRule .= "  --imm-dropdown-border: {$settings['dark_mode_dropdown_border']};\n";
			}

			if ( $darkRule !== '' ) {
				$rules[] = "@media (prefers-color-scheme: dark) {\n.imm-nav {\n{$darkRule}}}\n";
			}
		}

		return implode( "\n", $rules );
	}
}
