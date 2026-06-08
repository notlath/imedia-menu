<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class ReplacementsBlock implements ContentBlock {

	public function type(): string {
		return 'replacements';
	}

	public function title(): string {
		return __( 'Replacements', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'template'         => '',
			'parse_shortcodes' => false,
			'allowed_html'     => array(),
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$template = (string) ( $config['template'] ?? '' );

		if ( $template === '' ) {
			return sprintf(
				'<div class="imm-block imm-block--replacements"><p class="imm-empty">%s</p></div>',
				esc_html__( 'Replacements template is empty', 'imedia-menu' )
			);
		}

		$tokenMap = $this->buildTokenMap();

		$output = strtr( $template, $tokenMap );

		if ( ! empty( $config['parse_shortcodes'] ) ) {
			$output = do_shortcode( $output );
		}

		$allowed   = $this->resolveAllowedHtml( $config['allowed_html'] ?? array() );
		$sanitized = wp_kses( $output, $allowed );

		return sprintf(
			'<div class="imm-block imm-block--replacements">%s</div>',
			$sanitized
		);
	}

	private function buildTokenMap(): array {
		$user = function_exists( 'wp_get_current_user' ) ? wp_get_current_user() : null;

		$map = array(
			'{user_name}'  => is_object( $user ) && ! empty( $user->display_name ) ? (string) $user->display_name : '',
			'{user_email}' => is_object( $user ) && ! empty( $user->user_email ) ? (string) $user->user_email : '',
			'{user_id}'    => is_object( $user ) && ! empty( $user->ID ) ? (string) (int) $user->ID : '',
			'{site_title}' => (string) get_bloginfo( 'name' ),
			'{site_url}'   => (string) home_url(),
			'{date}'       => (string) date_i18n( get_option( 'date_format' ) ),
			'{time}'       => (string) date_i18n( get_option( 'time_format' ) ),
			'{ip}'         => isset( $_SERVER['REMOTE_ADDR'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				? (string) apply_filters( 'imedia_menu_replacements_ip', sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				: '',
		);

		if ( function_exists( 'WC' ) ) {
			$cart = WC()->cart ?? null;
			if ( $cart ) {
				$map['{cart_count}'] = (string) (int) $cart->get_cart_contents_count();
				$map['{cart_total}'] = (string) $cart->get_cart_subtotal();
			}
		}

		if ( ! isset( $map['{cart_count}'] ) ) {
			$map['{cart_count}'] = '';
		}
		if ( ! isset( $map['{cart_total}'] ) ) {
			$map['{cart_total}'] = '';
		}

		return (array) apply_filters( 'imedia_menu_replacements_token_map', $map );
	}

	private function resolveAllowedHtml( $allowed ): array {
		if ( is_array( $allowed ) && ! empty( $allowed ) ) {
			return $allowed;
		}
		return wp_kses_allowed_html( 'post' );
	}
}
