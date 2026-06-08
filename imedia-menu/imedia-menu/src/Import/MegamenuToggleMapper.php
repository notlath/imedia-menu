<?php

declare(strict_types=1);

namespace IMedia\Menu\Import;

final class MegamenuToggleMapper {

	public function mapToggleBlocks( array $toggleBlocks ): array {
		$mapped = array();

		foreach ( $toggleBlocks as $block ) {
			$type = $block['type'] ?? '';

			$converted = $this->convertBlock( $block, $type );
			if ( $converted !== null ) {
				$mapped[] = $converted;
			}
		}

		$mapped = $this->sortByOrder( $mapped );

		return $mapped;
	}

	private function convertBlock( array $block, string $type ): ?array {
		switch ( $type ) {
			case 'menu_toggle':
				return array(
					'type'  => 'menu_toggle',
					'order' => (int) ( $block['order'] ?? 0 ),
				);

			case 'menu_toggle_animated':
				return array(
					'type'  => 'menu_toggle_animated',
					'order' => (int) ( $block['order'] ?? 0 ),
				);

			case 'spacer':
				return array(
					'type'  => 'spacer',
					'order' => (int) ( $block['order'] ?? 0 ),
				);

			case 'logo':
				return array(
					'type'      => 'logo',
					'order'     => (int) ( $block['order'] ?? 0 ),
					'logo_id'   => (int) ( $block['logo_id'] ?? 0 ),
					'logo_link' => esc_url_raw( $block['logo_link'] ?? '' ),
					'align'     => 'left',
				);

			case 'search':
				return array(
					'type'        => 'search',
					'order'       => (int) ( $block['order'] ?? 0 ),
					'search_type' => sanitize_text_field( $block['search_type'] ?? 'text' ),
				);

			case 'html':
				return array(
					'type'  => 'html',
					'order' => (int) ( $block['order'] ?? 0 ),
					'html'  => wp_kses_post( $block['html'] ?? '' ),
				);

			case 'social':
				return array(
					'type'         => 'icon',
					'order'        => (int) ( $block['order'] ?? 0 ),
					'social_links' => $block['social_links'] ?? array(),
				);

			case 'menu_toggle_custom':
				return array(
					'type'  => 'custom',
					'order' => (int) ( $block['order'] ?? 0 ),
					'label' => sanitize_text_field( $block['label'] ?? __( 'Menu', 'imedia-menu' ) ),
				);

			default:
				return null;
		}
	}

	private function sortByOrder( array $blocks ): array {
		usort(
			$blocks,
			function ( array $a, array $b ) {
				return ( $a['order'] ?? 0 ) - ( $b['order'] ?? 0 );
			}
		);

		$indexed = array();
		foreach ( $blocks as $block ) {
			$indexed[] = $block;
		}

		return $indexed;
	}
}
