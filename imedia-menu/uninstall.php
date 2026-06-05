<?php
/**
 * Uninstall script — runs when plugin is deleted.
 *
 * @package IMedia_Menu
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

$settings = get_option('imedia_menu_settings', []);
$cleanup  = $settings['delete_data_on_uninstall'] ?? false;

if (!$cleanup) {
    return;
}

global $wpdb;

// Options
delete_option('imedia_menu_version');
delete_option('imedia_menu_db_version');
delete_option('imedia_menu_settings');

// User meta
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE %s",
        $wpdb->esc_like('imedia_menu_') . '%'
    )
);

// Post meta
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
        $wpdb->esc_like('_imedia_menu_') . '%'
    )
);

// Custom tables
$tables = [
    "{$wpdb->prefix}imedia_menu_panels",
    "{$wpdb->prefix}imedia_menu_templates",
    "{$wpdb->prefix}imedia_menu_revisions",
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange
}

// Transients
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
        $wpdb->esc_like('_transient_imedia_menu_') . '%',
        $wpdb->esc_like('_transient_timeout_imedia_menu_') . '%'
    )
);

// Generated CSS files
$upload_dir = wp_upload_dir();
$css_dir    = $upload_dir['basedir'] . '/imedia-menu';
if (is_dir($css_dir)) {
    array_map('unlink', glob($css_dir . '/*.css'));
    rmdir($css_dir);
}

wp_cache_flush();
