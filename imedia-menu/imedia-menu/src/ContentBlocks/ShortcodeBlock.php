<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class ShortcodeBlock implements ContentBlock {

	public function type(): string {
		return 'shortcode';
	}

	public function title(): string {
		return __( 'Shortcode', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$shortcode = $config['shortcode'] ?? '';

		if ( empty( $shortcode ) ) {
			return sprintf(
				'<div class="imm-block imm-block--shortcode"><p class="imm-empty">%s</p></div>',
				esc_html__( 'Enter a shortcode', 'imedia-menu' )
			);
		}

		$rendered = do_shortcode( $shortcode );

		return sprintf(
			'<div class="imm-block imm-block--shortcode">%s</div>',
			$rendered
		);
	}

	public function defaultConfig(): array {
		return array(
			'shortcode' => '',
		);
	}
}
