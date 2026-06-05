<?php

declare(strict_types=1);

namespace IMedia\Menu\Cache;

final class CacheInvalidator
{
    private MenuCache $cache;

    public function __construct()
    {
        $this->cache = new MenuCache();
    }

    public function invalidateMenu(int $menuId): void
    {
        $this->cache->delete("imedia_menu_{$menuId}");
        do_action('imedia_menu_cache_invalidated', $menuId);
    }

    public function invalidatePanel(int $menuItemId): void
    {
        $this->cache->delete("imedia_menu_panel_{$menuItemId}");
    }

    public function invalidateAll(): void
    {
        $this->cache->flush();
        do_action('imedia_menu_cache_flushed');
    }

    public function registerHooks(): void
    {
        add_action('wp_update_nav_menu', [$this, 'onMenuUpdate']);
        add_action('imedia_menu_settings_saved', [$this, 'onSettingsSaved']);
        add_action('imedia_menu_panel_saved', [$this, 'onPanelSaved']);
        add_action('switch_theme', [$this, 'invalidateAll']);
    }

    public function onMenuUpdate(int $menuId): void
    {
        $this->invalidateMenu($menuId);
    }

    public function onSettingsSaved(): void
    {
        $this->invalidateAll();
    }

    public function onPanelSaved(int $menuItemId): void
    {
        $this->invalidatePanel($menuItemId);

        $panel = new \IMedia\Menu\Database\PanelRepository();
        $record = $panel->findByMenuItem($menuItemId);

        if ($record) {
            $this->invalidateMenu((int) $record->menu_id);
        }
    }
}
