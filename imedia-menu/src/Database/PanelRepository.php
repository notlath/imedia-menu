<?php

declare(strict_types=1);

namespace IMedia\Menu\Database;

final class PanelRepository {

	private string $table;

	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . Schema::PANELS_TABLE;
	}

	public function findByMenuItem( int $menuItemId ): ?object {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE menu_item_id = %d LIMIT 1",
				$menuItemId
			)
		);

		return $row ? $this->hydrate( $row ) : null;
	}

	public function findByMenu( int $menuId ): array {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE menu_id = %d AND is_enabled = 1",
				$menuId
			)
		);

		return array_map( array( $this, 'hydrate' ), $rows );
	}

	public function save( int $menuItemId, int $menuId, array $data ): bool {
		global $wpdb;

		$existing = $this->findByMenuItem( $menuItemId );

		$fields = array(
			'menu_item_id'   => $menuItemId,
			'menu_id'        => $menuId,
			'is_enabled'     => $data['is_enabled'] ?? 1,
			'layout_type'    => $data['layout_type'] ?? 'columns',
			'panel_width'    => $data['panel_width'] ?? 'container',
			'custom_width'   => $data['custom_width'] ?? null,
			'column_count'   => $data['column_count'] ?? 3,
			'animation_type' => $data['animation_type'] ?? 'fade',
			'config'         => wp_json_encode( $data['config'] ?? array() ),
			'styles'         => isset( $data['styles'] ) ? wp_json_encode( $data['styles'] ) : null,
		);

		if ( $existing ) {
			return $wpdb->update( $this->table, $fields, array( 'id' => $existing->id ) ) !== false;
		}

		return $wpdb->insert( $this->table, $fields ) !== false;
	}

	public function delete( int $menuItemId ): bool {
		global $wpdb;

		return $wpdb->delete( $this->table, array( 'menu_item_id' => $menuItemId ) ) !== false;
	}

	public function deleteByMenu( int $menuId ): bool {
		global $wpdb;

		return $wpdb->delete( $this->table, array( 'menu_id' => $menuId ) ) !== false;
	}

	private function hydrate( object $row ): object {
		$row->config = json_decode( $row->config, true );
		$row->styles = $row->styles ? json_decode( $row->styles, true ) : null;

		return $row;
	}
}
