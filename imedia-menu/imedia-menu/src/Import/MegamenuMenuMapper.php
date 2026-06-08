<?php

declare(strict_types=1);

namespace IMedia\Menu\Import;

final class MegamenuMenuMapper {

	private MegamenuMapping $mapping;

	public function __construct() {
		$this->mapping = new MegamenuMapping();
	}

	public function mapItems( int $menuTermId, array $menusData ): array {
		$results = array(
			'items'  => 0,
			'errors' => array(),
		);

		if ( ! isset( $menusData[ $menuTermId ] ) ) {
			return $results;
		}

		$items = $menusData[ $menuTermId ]['items'] ?? array();

		foreach ( $items as $item ) {
			$itemId = $item['id'] ?? 0;
			if ( ! $itemId ) {
				continue;
			}

			$megameta = $item['megamenu_settings'] ?? array();
			$meta     = $this->mapping->mapMenuItemMeta( $megameta );

			foreach ( $meta as $key => $value ) {
				update_post_meta( $itemId, $key, $value );
			}

			++$results['items'];
		}

		return $results;
	}
}
