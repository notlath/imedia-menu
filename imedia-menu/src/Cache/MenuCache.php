<?php

declare(strict_types=1);

namespace IMedia\Menu\Cache;

use IMedia\Menu\Contracts\Cacheable;

final class MenuCache implements Cacheable
{
    private CacheKeyBuilder $keyBuilder;

    public function __construct()
    {
        $this->keyBuilder = new CacheKeyBuilder();
    }

    public function getMenuHtml(int $menuId, ?string &$cacheKey = null): ?string
    {
        $key = $this->keyBuilder->build($menuId);

        if ($cacheKey !== null) {
            $cacheKey = $key;
        }

        $cached = wp_cache_get($key, 'imedia_menu');

        if ($cached !== false) {
            return is_string($cached) ? $cached : null;
        }

        $transient = get_transient($key);

        return $transient !== false ? $transient : null;
    }

    public function setMenuHtml(int $menuId, string $html, int $duration = 3600): bool
    {
        $key = $this->keyBuilder->build($menuId);

        wp_cache_set($key, $html, 'imedia_menu', $duration);

        set_transient($key, $html, $duration);

        return true;
    }

    public function get(string $key): mixed
    {
        $cached = wp_cache_get($key, 'imedia_menu');

        if ($cached !== false) {
            return $cached;
        }

        return get_transient($key);
    }

    public function set(string $key, mixed $data, int $duration = 3600): bool
    {
        wp_cache_set($key, $data, 'imedia_menu', $duration);
        set_transient($key, $data, $duration);

        return true;
    }

    public function delete(string $key): bool
    {
        wp_cache_delete($key, 'imedia_menu');
        delete_transient($key);

        return true;
    }

    public function flush(): bool
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                $wpdb->esc_like('_transient_imedia_menu_') . '%',
                $wpdb->esc_like('_transient_timeout_imedia_menu_') . '%'
            )
        );

        return true;
    }
}
