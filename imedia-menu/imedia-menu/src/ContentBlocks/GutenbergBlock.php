<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class GutenbergBlock implements ContentBlock {

	public function type(): string {
		return 'gutenberg_block';
	}

	public function title(): string {
		return __( 'Gutenberg Block', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$blockName  = $config['block_name'] ?? '';
		$blockAttrs = $config['block_attrs'] ?? array();

		if ( empty( $blockName ) ) {
			return sprintf(
				'<div class="imm-block imm-block--gutenberg"><p class="imm-empty">%s</p></div>',
				esc_html__( 'Select a Gutenberg block', 'imedia-menu' )
			);
		}

		$rendered = render_block(
			array(
				'blockName'    => $blockName,
				'attrs'        => $blockAttrs,
				'innerContent' => $config['inner_content'] ?? array(),
			)
		);

		return sprintf(
			'<div class="imm-block imm-block--gutenberg">%s</div>',
			$rendered
		);
	}

	public function defaultConfig(): array {
		return array(
			'block_name'    => '',
			'block_attrs'   => array(),
			'inner_content' => array(),
		);
	}
}
