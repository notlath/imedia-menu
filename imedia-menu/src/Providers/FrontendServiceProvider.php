<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Frontend\Assets;
use IMedia\Menu\Frontend\MenuWalker;
use IMedia\Menu\Cache\MenuCache;

final class FrontendServiceProvider implements ServiceProvider
{
    private Assets $assets;

    public function register(): void
    {
        $this->assets = new Assets();
    }

    public function boot(): void
    {
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueue'], 100);
        add_filter('wp_nav_menu_args', [$this, 'filterMenuArgs'], 10, 2);
    }

    public function filterMenuArgs(array $args): array
    {
        $settings    = get_option('imedia_menu_settings', []);
        $enabled     = $settings['enabled'] ?? true;

        if (!$enabled) {
            return $args;
        }

        $location = $args['theme_location'] ?? '';

        if (empty($location)) {
            return $args;
        }

        $menuId = $this->getMenuIdFromLocation($location);

        if ($menuId === 0) {
            return $args;
        }

        $walker = new MenuWalker($menuId);

        $cache = new MenuCache();
        $cacheKey = null;
        $cached = $cache->getMenuHtml($menuId, $cacheKey);

        if ($cached !== null) {
            add_filter('wp_nav_menu', function (string $navHtml, object $navArgs) use ($cached, $cacheKey): string {
                if (isset($navArgs->walker) && $navArgs->walker instanceof MenuWalker) {
                    return $cached;
                }
                return $navHtml;
            }, 10, 2);
        }

        $args['walker']       = $walker;
        $args['container']    = 'nav';
        $args['container_class'] = $this->getContainerClass($location);
        $args['container_aria_label'] = $this->getMenuLabel($menuId);
        $args['menu_class']   = 'imm-menu';
        $args['items_wrap']   = $this->getItemsWrap($menuId);
        $args['fallback_cb']  = false;
        $args['echo']         = false;

        return $args;
    }

    private function getMenuIdFromLocation(string $location): int
    {
        $locations = get_nav_menu_locations();

        return (int) ($locations[$location] ?? 0);
    }

    private function getContainerClass(string $location): string
    {
        $settings = get_option('imedia_menu_settings', []);
        $classes  = ['imm-nav'];

        if (!empty($settings['transparent_mode'])) {
            $classes[] = 'imm-nav--transparent';
        }

        if (!empty($settings['sticky'])) {
            $classes[] = 'imm-nav--sticky';
        }

        $classes[] = 'imm-nav--location-' . $location;

        return implode(' ', $classes);
    }

    private function getMenuLabel(int $menuId): string
    {
        $menu = wp_get_nav_menu_object($menuId);

        return $menu ? $menu->name : __('Navigation', 'imedia-menu');
    }

    private function getItemsWrap(int $menuId): string
    {
        return sprintf(
            '<ul id="imm-menu-%d" class="%%2$s" role="menu">%%3$s</ul>',
            $menuId
        );
    }
}
