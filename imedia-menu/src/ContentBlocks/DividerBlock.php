<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class DividerBlock implements ContentBlock {

	public function type(): string {
		return 'divider';
	}

	public function title(): string {
		return __( 'Divider / Spacer', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$height = $config['height'] ?? '1px';
		$style  = $config['style'] ?? 'solid';
		$color  = $config['color'] ?? '#e0e0e0';
		$margin = $config['margin'] ?? '16px 0';

		$css = "height:{$height};border-top:{$height} {$style} {$color};margin:{$margin};";

		return sprintf(
			'<div class="imm-block imm-block--divider" style="%s" aria-hidden="true"></div>',
			esc_attr( $css )
		);
	}

	public function defaultConfig(): array {
		return array(
			'height' => '1px',
			'style'  => 'solid',
			'color'  => '#e0e0e0',
			'margin' => '16px 0',
		);
	}
}
