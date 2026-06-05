<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Admin\Settings\SettingsPage;
use IMedia\Menu\Admin\Assets as AdminAssets;

final class AdminServiceProvider implements ServiceProvider
{
    private SettingsPage $settingsPage;
    private AdminAssets $assets;

    public function register(): void
    {
        $this->settingsPage = new SettingsPage();
        $this->assets       = new AdminAssets();
    }

    public function boot(): void
    {
        add_action('admin_menu', [$this, 'addAdminPages'], 20);
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueue']);
        add_action('admin_bar_menu', [$this, 'addAdminBarLink'], 100);
    }

    public function addAdminPages(): void
    {
        add_submenu_page(
            'themes.php',
            __('iMedia Menu', 'imedia-menu'),
            __('iMedia Menu', 'imedia-menu'),
            apply_filters('imedia_menu_capability', 'edit_theme_options'),
            'imedia-menu',
            [$this->settingsPage, 'render'],
            30
        );
    }

    public function addAdminBarLink(\WP_Admin_Bar $adminBar): void
    {
        $settings = get_option('imedia_menu_settings', []);
        $showLink = $settings['admin_bar_preview'] ?? true;

        if (!$showLink) {
            return;
        }

        $adminBar->add_node([
            'id'    => 'imedia-menu-preview',
            'title' => __('iMedia Menu', 'imedia-menu'),
            'href'  => admin_url('themes.php?page=imedia-menu'),
            'parent' => 'appearance',
        ]);
    }
}
