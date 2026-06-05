<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Admin\MenuEditor\MenuItemFields;

final class MenuEditorServiceProvider implements ServiceProvider
{
    private MenuItemFields $fields;

    public function register(): void
    {
        $this->fields = new MenuItemFields();
    }

    public function boot(): void
    {
        add_action('wp_nav_menu_item_custom_fields', [$this->fields, 'renderFields'], 10, 4);
        add_action('wp_update_nav_menu_item', [$this->fields, 'saveFields'], 10, 3);
        add_filter('manage_nav-menus_columns', [$this->fields, 'addColumns']);
    }
}
