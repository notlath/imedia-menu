<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class TaxonomyListingBlock implements ContentBlock {

	public function type(): string {
		return 'taxonomy_listing';
	}

	public function title(): string {
		return __( 'Taxonomy Listing', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$taxonomy  = $config['taxonomy'] ?? 'category';
		$count     = min( (int) ( $config['count'] ?? 10 ), 50 );
		$orderby   = $config['orderby'] ?? 'name';
		$order     = $config['order'] ?? 'ASC';
		$hideEmpty = (bool) ( $config['hide_empty'] ?? false );

		$args = apply_filters(
			'imedia_menu_taxonomy_listing_args',
			array(
				'taxonomy'   => $taxonomy,
				'number'     => $count,
				'orderby'    => $orderby,
				'order'      => $order,
				'hide_empty' => $hideEmpty,
			),
			$config
		);

		$terms = get_terms( $args );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return sprintf(
				'<div class="imm-block imm-block--taxonomies"><p class="imm-empty">%s</p></div>',
				esc_html__( 'No terms found', 'imedia-menu' )
			);
		}

		$html = '<div class="imm-block imm-block--taxonomies"><ul class="imm-tax-list">';

		foreach ( $terms as $term ) {
			$html .= sprintf(
				'<li class="imm-tax-item"><a href="%s" class="imm-tax-link">%s</a></li>',
				esc_url( get_term_link( $term ) ),
				esc_html( $term->name )
			);
		}

		$html .= '</ul></div>';

		return $html;
	}

	public function defaultConfig(): array {
		return array(
			'taxonomy'   => 'category',
			'count'      => 10,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		);
	}
}
