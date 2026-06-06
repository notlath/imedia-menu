<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class HeadingBlock implements ContentBlock {

	public function type(): string {
		return 'heading';
	}

	public function title(): string {
		return __( 'Heading / Label', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$text  = $config['text'] ?? __( 'Heading', 'imedia-menu' );
		$level = $config['level'] ?? 'h3';

		$allowed = array( 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'div' );
		$tag     = in_array( $level, $allowed, true ) ? $level : 'h3';

		$styleAttr = '';
		if ( ! empty( $styles ) ) {
			$css = array();
			if ( isset( $styles['fontSize'] ) ) {
				$css[] = 'font-size:' . $styles['fontSize'];
			}
			if ( isset( $styles['color'] ) ) {
				$css[] = 'color:' . $styles['color'];
			}
			if ( ! empty( $css ) ) {
				$styleAttr = ' style="' . esc_attr( implode( ';', $css ) ) . '"';
			}
		}

		return sprintf(
			'<%1$s class="imm-block imm-block--heading"%3$s>%2$s</%1$s>',
			tag_escape( $tag ),
			esc_html( $text ),
			$styleAttr
		);
	}

	public function defaultConfig(): array {
		return array(
			'text'  => __( 'Heading', 'imedia-menu' ),
			'level' => 'h3',
		);
	}
}
