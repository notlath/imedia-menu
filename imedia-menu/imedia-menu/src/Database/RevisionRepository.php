<?php

declare(strict_types=1);

namespace IMedia\Menu\Database;

final class RevisionRepository {

	private string $table;

	private const MAX_REVISIONS = 50;

	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . Schema::REVISIONS_TABLE;
	}

	public function findByPanel( int $panelId, int $limit = 20 ): array {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE panel_id = %d ORDER BY created_at DESC LIMIT %d",
				$panelId,
				$limit
			)
		);

		return array_map( array( $this, 'hydrate' ), $rows );
	}

	public function create( int $panelId, int $menuItemId, array $config, ?array $styles, int $userId ): ?int {
		global $wpdb;

		$result = $wpdb->insert(
			$this->table,
			array(
				'panel_id'     => $panelId,
				'menu_item_id' => $menuItemId,
				'config'       => wp_json_encode( $config ),
				'styles'       => $styles ? wp_json_encode( $styles ) : null,
				'user_id'      => $userId,
			)
		);

		if ( $result ) {
			$this->enforceLimit( $panelId );
			return (int) $wpdb->insert_id;
		}

		return null;
	}

	public function restore( int $revisionId ): ?object {
		global $wpdb;

		$revision = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE id = %d",
				$revisionId
			)
		);

		return $revision ? $this->hydrate( $revision ) : null;
	}

	public function deleteByPanel( int $panelId ): bool {
		global $wpdb;

		return $wpdb->delete( $this->table, array( 'panel_id' => $panelId ) ) !== false;
	}

	private function enforceLimit( int $panelId ): void {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$this->table}
                WHERE panel_id = %d
                AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM {$this->table}
                        WHERE panel_id = %d
                        ORDER BY created_at DESC
                        LIMIT %d
                    ) AS keep_ids
                )",
				$panelId,
				$panelId,
				self::MAX_REVISIONS
			)
		);
	}

	private function hydrate( object $row ): object {
		$row->config = json_decode( $row->config, true );
		$row->styles = $row->styles ? json_decode( $row->styles, true ) : null;

		return $row;
	}
}
