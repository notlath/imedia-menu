<?php

declare(strict_types=1);

namespace IMedia\Menu\Database;

final class Schema {

	public const PANELS_TABLE    = 'imedia_menu_panels';
	public const TEMPLATES_TABLE = 'imedia_menu_templates';
	public const REVISIONS_TABLE = 'imedia_menu_revisions';

	public function create(): void {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset = $wpdb->get_charset_collate();

		$panels = $this->getPanelsTableSql( $wpdb->prefix, $charset );
		dbDelta( $panels );

		$templates = $this->getTemplatesTableSql( $wpdb->prefix, $charset );
		dbDelta( $templates );

		$revisions = $this->getRevisionsTableSql( $wpdb->prefix, $charset );
		dbDelta( $revisions );
	}

	public function validateRequirements(): void {
		global $wpdb;

		$mysql_version = $wpdb->db_version();

		if ( version_compare( $mysql_version, '5.7', '<' ) ) {
			deactivate_plugins( BASENAME );
			wp_die(
				esc_html__( 'iMedia Menu requires MySQL 5.7+ or MariaDB 10.2+.', 'imedia-menu' ),
				esc_html__( 'Plugin Activation Error', 'imedia-menu' ),
				array( 'back_link' => true )
			);
		}
	}

	public function drop(): void {
		global $wpdb;

		$tables = array(
			$wpdb->prefix . self::PANELS_TABLE,
			$wpdb->prefix . self::TEMPLATES_TABLE,
			$wpdb->prefix . self::REVISIONS_TABLE,
		);

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
		}
	}

	private function getPanelsTableSql( string $prefix, string $charset ): string {
		$table = $prefix . self::PANELS_TABLE;

		return "CREATE TABLE {$table} (
            id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            menu_item_id    BIGINT UNSIGNED NOT NULL,
            menu_id         BIGINT UNSIGNED NOT NULL,
            is_enabled      TINYINT(1) NOT NULL DEFAULT 1,
            layout_type     VARCHAR(20) NOT NULL DEFAULT 'columns',
            panel_width     VARCHAR(20) NOT NULL DEFAULT 'container',
            custom_width    VARCHAR(20) DEFAULT NULL,
            column_count    TINYINT UNSIGNED NOT NULL DEFAULT 3,
            animation_type  VARCHAR(20) NOT NULL DEFAULT 'fade',
            config          JSON NOT NULL,
            styles          JSON DEFAULT NULL,
            created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY idx_menu_item (menu_item_id),
            KEY idx_menu (menu_id),
            KEY idx_enabled (is_enabled)
        ) {$charset};";
	}

	private function getTemplatesTableSql( string $prefix, string $charset ): string {
		$table = $prefix . self::TEMPLATES_TABLE;

		return "CREATE TABLE {$table} (
            id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name        VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            config      JSON NOT NULL,
            styles      JSON DEFAULT NULL,
            meta        JSON DEFAULT NULL,
            created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY idx_name (name(191))
        ) {$charset};";
	}

	private function getRevisionsTableSql( string $prefix, string $charset ): string {
		$table = $prefix . self::REVISIONS_TABLE;

		return "CREATE TABLE {$table} (
            id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            panel_id        BIGINT UNSIGNED NOT NULL,
            menu_item_id    BIGINT UNSIGNED NOT NULL,
            config          JSON NOT NULL,
            styles          JSON DEFAULT NULL,
            user_id         BIGINT UNSIGNED NOT NULL,
            created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY idx_panel (panel_id),
            KEY idx_menu_item (menu_item_id),
            KEY idx_created (created_at)
        ) {$charset};";
	}
}
