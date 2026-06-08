<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class PageCondition implements VisibilityCondition {

	public function type(): string {
		return 'page';
	}

	public function label(): string {
		return __( 'Page/Post Conditions', 'imedia-menu' );
	}

	public function evaluate( array $config ): bool {
		$mode       = $config['mode'] ?? 'show_on';
		$pageIds    = array_map( 'intval', $config['page_ids'] ?? array() );
		$postTypes  = $config['post_types'] ?? array();
		$taxonomies = $config['taxonomies'] ?? array();
		$isSearch   = (bool) ( $config['is_search'] ?? false );
		$isArchive  = (bool) ( $config['is_archive'] ?? false );

		$matched = false;

		if ( ! empty( $pageIds ) ) {
			$currentId = get_queried_object_id();
			$matched   = in_array( $currentId, $pageIds, true );
		}

		if ( ! $matched && ! empty( $postTypes ) ) {
			$matched = is_singular( $postTypes );
		}

		if ( ! $matched && ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $tax ) {
				if ( is_tax( $tax ) ) {
					$matched = true;
					break;
				}
				if ( is_category() && $tax === 'category' ) {
					$matched = true;
					break;
				}
			}
		}

		if ( ! $matched && $isSearch ) {
			$matched = is_search();
		}

		if ( ! $matched && $isArchive ) {
			$matched = is_archive();
		}

		return $mode === 'show_on' ? $matched : ! $matched;
	}
}
