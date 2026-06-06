<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class SearchBlock implements ContentBlock {

	public function type(): string {
		return 'search';
	}

	public function title(): string {
		return __( 'Search Bar', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$placeholder = $config['placeholder'] ?? __( 'Search...', 'imedia-menu' );
		$style       = $config['style'] ?? 'full';
		$iconOnly    = (bool) ( $config['icon_only'] ?? false );

		$html = sprintf(
			'<div class="imm-block imm-block--search imm-search--%s">',
			esc_attr( $style )
		);

		if ( $iconOnly ) {
			$html .= sprintf(
				'<button class="imm-search-toggle" aria-label="%s">',
				esc_attr__( 'Toggle search', 'imedia-menu' )
			);
			$html .= '<span class="dashicons dashicons-search" aria-hidden="true"></span>';
			$html .= '</button>';
		} else {
			$formHtml = get_search_form( false );

			if ( $formHtml ) {
				$html .= preg_replace(
					'/<label>/',
					sprintf(
						'<label><span class="imm-sr-only">%s</span>',
						esc_html__( 'Search', 'imedia-menu' )
					),
					$formHtml,
					1
				);
			} else {
				$html .= sprintf(
					'<form role="search" method="get" class="imm-search-form" action="%s">',
					esc_url( home_url( '/' ) )
				);
				$html .= sprintf(
					'<input type="search" class="imm-search-input" placeholder="%s" value="%s" name="s" />',
					esc_attr( $placeholder ),
					esc_attr( get_search_query() )
				);
				$html .= sprintf(
					'<button type="submit" class="imm-search-submit" aria-label="%s">',
					esc_attr__( 'Search', 'imedia-menu' )
				);
				$html .= '<span class="dashicons dashicons-search" aria-hidden="true"></span>';
				$html .= '</button></form>';
			}
		}

		$html .= '</div>';

		return apply_filters( 'imedia_menu_search_form_html', $html, $config );
	}

	public function defaultConfig(): array {
		return array(
			'placeholder' => __( 'Search...', 'imedia-menu' ),
			'style'       => 'full',
			'icon_only'   => false,
		);
	}
}
