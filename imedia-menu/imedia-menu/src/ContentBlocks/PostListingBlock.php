<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class PostListingBlock implements ContentBlock {

	public function type(): string {
		return 'post_listing';
	}

	public function title(): string {
		return __( 'Post/Page Listing', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$postType    = $config['post_type'] ?? 'post';
		$count       = min( (int) ( $config['count'] ?? 5 ), 20 );
		$orderby     = $config['orderby'] ?? 'date';
		$order       = $config['order'] ?? 'DESC';
		$showThumb   = (bool) ( $config['show_thumbnail'] ?? false );
		$showExcerpt = (bool) ( $config['show_excerpt'] ?? false );
		$ajaxLoading = (bool) ( $config['ajax_loading'] ?? false );

		if ( $ajaxLoading ) {
			return sprintf(
				'<div class="imm-block imm-block--posts" data-ajax-load="1" data-config="%s">%s</div>',
				esc_attr( wp_json_encode( $config ) ),
				esc_html__( 'Loading...', 'imedia-menu' )
			);
		}

		$args = apply_filters(
			'imedia_menu_post_listing_query_args',
			array(
				'post_type'      => $postType,
				'posts_per_page' => $count,
				'orderby'        => $orderby,
				'order'          => $order,
				'no_found_rows'  => true,
			),
			$config
		);

		if ( ! empty( $config['taxonomy_filter'] ) ) {
			$taxFilter         = $config['taxonomy_filter'];
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxFilter['taxonomy'] ?? '',
					'field'    => 'term_id',
					'terms'    => $taxFilter['terms'] ?? array(),
				),
			);
		}

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return sprintf(
				'<div class="imm-block imm-block--posts"><p class="imm-empty">%s</p></div>',
				esc_html__( 'No posts found', 'imedia-menu' )
			);
		}

		$html = '<div class="imm-block imm-block--posts"><ul class="imm-posts-list">';

		while ( $query->have_posts() ) {
			$query->the_post();

			$html .= '<li class="imm-posts-item">';
			$html .= sprintf(
				'<a href="%s" class="imm-posts-link">',
				esc_url( get_permalink() )
			);

			if ( $showThumb && has_post_thumbnail() ) {
				$html .= get_the_post_thumbnail(
					null,
					'thumbnail',
					array(
						'class'   => 'imm-posts-thumb',
						'loading' => 'lazy',
					)
				);
			}

			$html .= sprintf(
				'<span class="imm-posts-title">%s</span>',
				esc_html( get_the_title() )
			);

			if ( $showExcerpt ) {
				$html .= sprintf(
					'<span class="imm-posts-excerpt">%s</span>',
					esc_html( get_the_excerpt() )
				);
			}

			$html .= '</a></li>';
		}

		$html .= '</ul></div>';

		wp_reset_postdata();

		return $html;
	}

	public function defaultConfig(): array {
		return array(
			'post_type'       => 'post',
			'count'           => 5,
			'orderby'         => 'date',
			'order'           => 'DESC',
			'show_thumbnail'  => false,
			'show_excerpt'    => false,
			'ajax_loading'    => false,
			'taxonomy_filter' => array(),
		);
	}
}
