<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;

final class RestApiServiceProvider implements ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(): void
    {
        $this->registerMenuRoutes();
        $this->registerPanelRoutes();
        $this->registerTemplateRoutes();
        $this->registerRevisionRoutes();
        $this->registerSettingsRoutes();
        $this->registerCacheRoutes();
    }

    private function registerMenuRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/menus', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getMenus'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/menus/(?P<id>\d+)', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getMenu'],
            'permission_callback' => [$this, 'checkPermission'],
            'args'                => [
                'id' => ['required' => true, 'validate_callback' => 'is_numeric'],
            ],
        ]);
    }

    private function registerPanelRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/panels/(?P<menu_item_id>\d+)', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getPanel'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/panels/(?P<menu_item_id>\d+)', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'savePanel'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/panels/(?P<menu_item_id>\d+)', [
            'methods'             => \WP_REST_Server::DELETABLE,
            'callback'            => [$this, 'deletePanel'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    private function registerTemplateRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/templates', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getTemplates'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/templates', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'createTemplate'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/templates/(?P<id>\d+)', [
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => [$this, 'updateTemplate'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/templates/(?P<id>\d+)', [
            'methods'             => \WP_REST_Server::DELETABLE,
            'callback'            => [$this, 'deleteTemplate'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    private function registerRevisionRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/revisions/(?P<panel_id>\d+)', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getRevisions'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/revisions/(?P<id>\d+)/restore', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'restoreRevision'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    private function registerSettingsRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/settings', [
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => [$this, 'getSettings'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);

        register_rest_route('imedia-menu/v1', '/settings', [
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => [$this, 'updateSettings'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    private function registerCacheRoutes(): void
    {
        register_rest_route('imedia-menu/v1', '/cache/flush', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'flushCache'],
            'permission_callback' => [$this, 'checkPermission'],
        ]);
    }

    public function checkPermission(): bool
    {
        return current_user_can(apply_filters('imedia_menu_capability', 'edit_theme_options'));
    }

    public function getMenus(): \WP_REST_Response
    {
        $menus = wp_get_nav_menus();
        $data  = [];

        foreach ($menus as $menu) {
            $data[] = [
                'id'      => $menu->term_id,
                'name'    => $menu->name,
                'slug'    => $menu->slug,
                'count'   => $menu->count,
                'locations' => get_nav_menu_locations(),
            ];
        }

        return new \WP_REST_Response($data, 200);
    }

    public function getMenu(\WP_REST_Request $request): \WP_REST_Response
    {
        $menuId = (int) $request->get_param('id');
        $menu   = wp_get_nav_menu_object($menuId);

        if (!$menu) {
            return new \WP_REST_Response(['error' => 'Menu not found'], 404);
        }

        $items = wp_get_nav_menu_items($menuId);

        return new \WP_REST_Response([
            'id'      => $menu->term_id,
            'name'    => $menu->name,
            'items'   => $items ?: [],
        ], 200);
    }

    public function getPanel(\WP_REST_Request $request): \WP_REST_Response
    {
        $menuItemId = (int) $request->get_param('menu_item_id');
        $repository = new \IMedia\Menu\Database\PanelRepository();
        $panel      = $repository->findByMenuItem($menuItemId);

        if (!$panel) {
            return new \WP_REST_Response(['config' => null, 'styles' => null], 200);
        }

        return new \WP_REST_Response([
            'id'            => (int) $panel->id,
            'menu_item_id'  => (int) $panel->menu_item_id,
            'menu_id'       => (int) $panel->menu_id,
            'is_enabled'    => (bool) $panel->is_enabled,
            'layout_type'   => $panel->layout_type,
            'panel_width'   => $panel->panel_width,
            'custom_width'  => $panel->custom_width,
            'column_count'  => (int) $panel->column_count,
            'animation_type' => $panel->animation_type,
            'config'        => $panel->config,
            'styles'        => $panel->styles,
            'created_at'    => $panel->created_at,
            'updated_at'    => $panel->updated_at,
        ], 200);
    }

    public function savePanel(\WP_REST_Request $request): \WP_REST_Response
    {
        $menuItemId = (int) $request->get_param('menu_item_id');
        $body       = $request->get_json_params();
        $menuId     = (int) ($body['menu_id'] ?? 0);

        if ($menuId === 0) {
            return new \WP_REST_Response(['error' => 'menu_id is required'], 400);
        }

        $repository = new \IMedia\Menu\Database\PanelRepository();
        $result     = $repository->save($menuItemId, $menuId, $body);

        if (!$result) {
            return new \WP_REST_Response(['error' => 'Failed to save panel'], 500);
        }

        $revisionRepo = new \IMedia\Menu\Database\RevisionRepository();
        $panel = $repository->findByMenuItem($menuItemId);

        if ($panel && !empty($panel->id)) {
            $revisionRepo->create(
                (int) $panel->id,
                $menuItemId,
                $body['config'] ?? [],
                $body['styles'] ?? null,
                get_current_user_id()
            );
        }

        do_action('imedia_menu_panel_saved', $menuItemId);

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function deletePanel(\WP_REST_Request $request): \WP_REST_Response
    {
        $menuItemId = (int) $request->get_param('menu_item_id');
        $repository = new \IMedia\Menu\Database\PanelRepository();
        $repository->delete($menuItemId);

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function getTemplates(): \WP_REST_Response
    {
        $repository = new \IMedia\Menu\Database\TemplateRepository();
        $templates  = $repository->findAll();

        return new \WP_REST_Response($templates, 200);
    }

    public function createTemplate(\WP_REST_Request $request): \WP_REST_Response
    {
        $body   = $request->get_json_params();
        $repository = new \IMedia\Menu\Database\TemplateRepository();
        $id     = $repository->create($body);

        if (!$id) {
            return new \WP_REST_Response(['error' => 'Failed to create template'], 500);
        }

        do_action('imedia_menu_template_saved', $id, $body);

        return new \WP_REST_Response(['id' => $id], 201);
    }

    public function updateTemplate(\WP_REST_Request $request): \WP_REST_Response
    {
        $id   = (int) $request->get_param('id');
        $body = $request->get_json_params();

        $repository = new \IMedia\Menu\Database\TemplateRepository();
        $repository->update($id, $body);

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function deleteTemplate(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = (int) $request->get_param('id');

        $repository = new \IMedia\Menu\Database\TemplateRepository();
        $repository->delete($id);

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function getRevisions(\WP_REST_Request $request): \WP_REST_Response
    {
        $panelId = (int) $request->get_param('panel_id');

        $repository = new \IMedia\Menu\Database\RevisionRepository();
        $revisions  = $repository->findByPanel($panelId);

        return new \WP_REST_Response($revisions, 200);
    }

    public function restoreRevision(\WP_REST_Request $request): \WP_REST_Response
    {
        $id = (int) $request->get_param('id');

        $repository = new \IMedia\Menu\Database\RevisionRepository();
        $revision   = $repository->restore($id);

        if (!$revision) {
            return new \WP_REST_Response(['error' => 'Revision not found'], 404);
        }

        $panelRepo = new \IMedia\Menu\Database\PanelRepository();
        $panel     = $panelRepo->findByMenuItem((int) $revision->menu_item_id);

        if ($panel) {
            $panelRepo->save(
                (int) $revision->menu_item_id,
                (int) $panel->menu_id,
                [
                    'config'  => $revision->config,
                    'styles'  => $revision->styles,
                ]
            );
        }

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function getSettings(): \WP_REST_Response
    {
        $settings = get_option('imedia_menu_settings', []);

        return new \WP_REST_Response($settings, 200);
    }

    public function updateSettings(\WP_REST_Request $request): \WP_REST_Response
    {
        $body     = $request->get_json_params();
        $settings = get_option('imedia_menu_settings', []);
        $settings = array_merge($settings, $body);

        update_option('imedia_menu_settings', $settings);

        do_action('imedia_menu_settings_saved', $settings);

        return new \WP_REST_Response(['success' => true], 200);
    }

    public function flushCache(): \WP_REST_Response
    {
        $invalidator = new \IMedia\Menu\Cache\CacheInvalidator();
        $invalidator->invalidateAll();

        return new \WP_REST_Response(['success' => true], 200);
    }
}
