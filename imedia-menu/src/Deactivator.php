<?php

declare(strict_types=1);

namespace IMedia\Menu;

final class Deactivator
{
    public static function deactivate(): void
    {
        delete_transient('imedia_menu_activated');
        self::clearCache();
        flush_rewrite_rules();
    }

    private static function clearCache(): void
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                $wpdb->esc_like('_transient_imedia_menu_') . '%',
                $wpdb->esc_like('_transient_timeout_imedia_menu_') . '%'
            )
        );
    }
}
