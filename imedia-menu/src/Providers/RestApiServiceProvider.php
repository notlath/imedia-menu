<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Contracts\ServiceProvider;

final class RestApiServiceProvider implements ServiceProvider {

	public function register(): void {
	}

	public function boot(): void {
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}

	public function registerRoutes(): void {
		$this->registerMenuRoutes();
		$this->registerPanelRoutes();
		$this->registerTemplateRoutes();
		$this->registerRevisionRoutes();
		$this->registerSettingsRoutes();
		$this->registerSettingLocationRoutes();
		$this->registerCacheRoutes();
		$this->registerExportImportRoutes();
		$this->registerIconRoutes();
		$this->registerContentRoutes();
	}

	private function registerMenuRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/menus',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getMenus' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/menus/(?P<id>\d+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getMenu' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args'                => array(
					'id' => array(
						'required'          => true,
						'validate_callback' => 'is_numeric',
					),
				),
			)
		);
	}

	private function registerPanelRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/panels/(?P<menu_item_id>\d+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getPanel' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/panels/(?P<menu_item_id>\d+)',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'savePanel' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/panels/(?P<menu_item_id>\d+)',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'deletePanel' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerTemplateRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/templates',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getTemplates' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/templates',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'createTemplate' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/templates/(?P<id>\d+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'updateTemplate' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/templates/(?P<id>\d+)',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'deleteTemplate' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerRevisionRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/revisions/(?P<panel_id>\d+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getRevisions' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/revisions/(?P<id>\d+)/restore',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'restoreRevision' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerSettingsRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/settings',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getSettings' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/settings',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'updateSettings' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/settings',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'updateSettings' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerSettingLocationRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/menus/locations',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getMenuLocations' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/settings/locations',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getAllLocationOverrides' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/settings/location/(?P<slug>[a-z0-9_-]+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'saveLocationOverrides' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'validate_callback' => 'sanitize_title_with_dashes',
					),
				),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/settings/location/(?P<slug>[a-z0-9_-]+)',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'saveLocationOverrides' ),
				'permission_callback' => array( $this, 'checkPermission' ),
				'args'                => array(
					'slug' => array(
						'required'          => true,
						'validate_callback' => 'sanitize_title_with_dashes',
					),
				),
			)
		);
	}

	public function getMenuLocations(): \WP_REST_Response {
		$registered = get_registered_nav_menus();
		$assigned   = get_nav_menu_locations();
		$data       = array();

		foreach ( $registered as $slug => $name ) {
			$data[] = array(
				'slug'    => $slug,
				'name'    => $name,
				'hasMenu' => isset( $assigned[ $slug ] ) && $assigned[ $slug ] > 0,
			);
		}

		return new \WP_REST_Response( $data, 200 );
	}

	public function getAllLocationOverrides(): \WP_REST_Response {
		$overrides = LocationOverrides::getAll();

		return new \WP_REST_Response( $overrides, 200 );
	}

	public function saveLocationOverrides( \WP_REST_Request $request ): \WP_REST_Response {
		$slug      = sanitize_title( $request->get_param( 'slug' ) );
		$body      = $request->get_json_params();
		$overrides = is_array( $body ) ? $body : array();

		$registered = get_registered_nav_menus();

		if ( ! isset( $registered[ $slug ] ) ) {
			return new \WP_REST_Response( array( 'error' => 'Invalid location slug' ), 400 );
		}

		LocationOverrides::setForLocation( $slug, $overrides );

		$merged = LocationOverrides::mergeWithGlobal(
			get_option( 'imedia_menu_settings', array() ),
			$slug
		);

		do_action( 'imedia_menu_location_overrides_saved', $slug, $overrides );
		do_action( 'imedia_menu_settings_saved', $merged );

		return new \WP_REST_Response(
			array(
				'success' => true,
				'slug'    => $slug,
			),
			200
		);
	}

	private function registerCacheRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/cache/flush',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'flushCache' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerExportImportRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/export',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'exportData' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/import',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'importData' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerIconRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/icons',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'getIcons' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/icons/svg',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'uploadSvgIcon' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	private function registerContentRoutes(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/content/posts',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'queryPosts' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);

		register_rest_route(
			'imedia-menu/v1',
			'/content/taxonomies',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'queryTaxonomies' ),
				'permission_callback' => array( $this, 'checkPermission' ),
			)
		);
	}

	public function exportData(): \WP_REST_Response {
		$exporter = new \IMedia\Menu\Export\Exporter();
		$data     = $exporter->export();

		return new \WP_REST_Response( $data, 200 );
	}

	public function importData( \WP_REST_Request $request ): \WP_REST_Response {
		$body     = $request->get_json_params();
		$importer = new \IMedia\Menu\Export\Importer();

		if ( isset( $body['json'] ) ) {
			$result = $importer->importFromJson( $body['json'] );
		} else {
			$result = $importer->import( $body );
		}

		return new \WP_REST_Response( $result, empty( $result['errors'] ) ? 200 : 400 );
	}

	public function getIcons(): \WP_REST_Response {
		$settings = get_option( 'imedia_menu_settings', array() );
		$manager  = new \IMedia\Menu\Icons\IconManager();

		$enabledProviders = $settings['icon_providers'] ?? array( 'dashicons' );

		if ( in_array( 'dashicons', $enabledProviders, true ) ) {
			$manager->register( new \IMedia\Menu\Icons\Providers\DashiconsProvider() );
		}

		if ( in_array( 'fontawesome', $enabledProviders, true ) ) {
			$manager->register( new \IMedia\Menu\Icons\Providers\FontAwesomeProvider() );
		}

		if ( in_array( 'custom_svg', $enabledProviders, true ) ) {
			$manager->register( new \IMedia\Menu\Icons\Providers\CustomSvgProvider() );
		}

		return new \WP_REST_Response( $manager->getAvailableIcons(), 200 );
	}

	public function uploadSvgIcon( \WP_REST_Request $request ): \WP_REST_Response {
		$files = $request->get_file_params();

		if ( empty( $files['file'] ) || ! isset( $files['file']['tmp_name'] ) ) {
			return new \WP_REST_Response( array( 'error' => __( 'No file provided.', 'imedia-menu' ) ), 400 );
		}

		$file       = $files['file'];
		$mimeType   = $file['type'] ?? '';
		$extension  = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

		if ( $extension !== 'svg' || ( $mimeType !== 'image/svg+xml' && strpos( $mimeType, 'svg' ) === false ) ) {
			return new \WP_REST_Response( array( 'error' => __( 'Only SVG files are allowed.', 'imedia-menu' ) ), 400 );
		}

		$provider    = new \IMedia\Menu\Icons\Providers\CustomSvgProvider();
		$attachmentId = $provider->uploadSvg( $file['tmp_name'] );

		if ( $attachmentId === null ) {
			return new \WP_REST_Response( array( 'error' => __( 'Failed to upload SVG.', 'imedia-menu' ) ), 500 );
		}

		return new \WP_REST_Response(
			array(
				'id'    => $attachmentId,
				'title' => get_the_title( $attachmentId ),
				'url'   => wp_get_attachment_url( $attachmentId ),
			),
			201
		);
	}

	public function queryPosts( \WP_REST_Request $request ): \WP_REST_Response {
		$search    = sanitize_text_field( $request->get_param( 'search' ) ?? '' );
		$postType  = sanitize_text_field( $request->get_param( 'post_type' ) ?? 'any' );
		$perPage   = min( (int) ( $request->get_param( 'per_page' ) ?? 20 ), 100 );

		$args = array(
			'post_type'      => $postType === 'any' ? array() : $postType,
			'posts_per_page' => $perPage,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		);

		if ( $search !== '' ) {
			$args['s'] = $search;
		}

		if ( $postType === 'any' ) {
			$args['post_type'] = array();
		}

		$query  = new \WP_Query( $args );
		$posts  = $query->posts;
		$result = array();

		foreach ( $posts as $post ) {
			$result[] = array(
				'id'        => $post->ID,
				'title'     => $post->post_title,
				'post_type' => $post->post_type,
				'date'      => $post->post_date,
			);
		}

		return new \WP_REST_Response( $result, 200 );
	}

	public function queryTaxonomies( \WP_REST_Request $request ): \WP_REST_Response {
		$search     = sanitize_text_field( $request->get_param( 'search' ) ?? '' );
		$taxonomy   = sanitize_text_field( $request->get_param( 'taxonomy' ) ?? '' );
		$perPage    = min( (int) ( $request->get_param( 'per_page' ) ?? 20 ), 100 );

		$args = array(
			'number'     => $perPage,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		);

		if ( $taxonomy !== '' ) {
			$args['taxonomy'] = $taxonomy;
		}

		if ( $search !== '' ) {
			$args['search'] = $search;
		}

		$terms  = get_terms( $args );
		$result = array();

		if ( is_wp_error( $terms ) ) {
			return new \WP_REST_Response( array(), 200 );
		}

		foreach ( $terms as $term ) {
			$result[] = array(
				'id'       => $term->term_id,
				'name'     => $term->name,
				'slug'     => $term->slug,
				'taxonomy' => $term->taxonomy,
				'count'    => $term->count,
			);
		}

		return new \WP_REST_Response( $result, 200 );
	}

	public function checkPermission(): bool {
		return current_user_can( apply_filters( 'imedia_menu_capability', 'edit_theme_options' ) );
	}

	public function getMenus(): \WP_REST_Response {
		$menus = wp_get_nav_menus();
		$data  = array();

		foreach ( $menus as $menu ) {
			$data[] = array(
				'id'        => $menu->term_id,
				'name'      => $menu->name,
				'slug'      => $menu->slug,
				'count'     => $menu->count,
				'locations' => get_nav_menu_locations(),
			);
		}

		return new \WP_REST_Response( $data, 200 );
	}

	public function getMenu( \WP_REST_Request $request ): \WP_REST_Response {
		$menuId = (int) $request->get_param( 'id' );
		$menu   = wp_get_nav_menu_object( $menuId );

		if ( ! $menu ) {
			return new \WP_REST_Response( array( 'error' => 'Menu not found' ), 404 );
		}

		$items = wp_get_nav_menu_items( $menuId );

		return new \WP_REST_Response(
			array(
				'id'    => $menu->term_id,
				'name'  => $menu->name,
				'items' => $items ?: array(),
			),
			200
		);
	}

	public function getPanel( \WP_REST_Request $request ): \WP_REST_Response {
		$menuItemId = (int) $request->get_param( 'menu_item_id' );
		$repository = new \IMedia\Menu\Database\PanelRepository();
		$panel      = $repository->findByMenuItem( $menuItemId );

		if ( ! $panel ) {
			return new \WP_REST_Response(
				array(
					'config' => null,
					'styles' => null,
				),
				200
			);
		}

		return new \WP_REST_Response(
			array(
				'id'             => (int) $panel->id,
				'menu_item_id'   => (int) $panel->menu_item_id,
				'menu_id'        => (int) $panel->menu_id,
				'is_enabled'     => (bool) $panel->is_enabled,
				'layout_type'    => $panel->layout_type,
				'panel_width'    => $panel->panel_width,
				'custom_width'   => $panel->custom_width,
				'column_count'   => (int) $panel->column_count,
				'animation_type' => $panel->animation_type,
				'config'         => $panel->config,
				'styles'         => $panel->styles,
				'created_at'     => $panel->created_at,
				'updated_at'     => $panel->updated_at,
			),
			200
		);
	}

	public function savePanel( \WP_REST_Request $request ): \WP_REST_Response {
		$menuItemId = (int) $request->get_param( 'menu_item_id' );
		$body       = $request->get_json_params();
		$menuId     = (int) ( $body['menu_id'] ?? 0 );

		if ( $menuId === 0 ) {
			return new \WP_REST_Response( array( 'error' => 'menu_id is required' ), 400 );
		}

		$repository = new \IMedia\Menu\Database\PanelRepository();
		$result     = $repository->save( $menuItemId, $menuId, $body );

		if ( ! $result ) {
			return new \WP_REST_Response( array( 'error' => 'Failed to save panel' ), 500 );
		}

		$revisionRepo = new \IMedia\Menu\Database\RevisionRepository();
		$panel        = $repository->findByMenuItem( $menuItemId );

		if ( $panel && ! empty( $panel->id ) ) {
			$revisionRepo->create(
				(int) $panel->id,
				$menuItemId,
				$body['config'] ?? array(),
				$body['styles'] ?? null,
				get_current_user_id()
			);
		}

		do_action( 'imedia_menu_panel_saved', $menuItemId );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function deletePanel( \WP_REST_Request $request ): \WP_REST_Response {
		$menuItemId = (int) $request->get_param( 'menu_item_id' );
		$repository = new \IMedia\Menu\Database\PanelRepository();
		$repository->delete( $menuItemId );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function getTemplates(): \WP_REST_Response {
		$repository = new \IMedia\Menu\Database\TemplateRepository();
		$templates  = $repository->findAll();

		return new \WP_REST_Response( $templates, 200 );
	}

	public function createTemplate( \WP_REST_Request $request ): \WP_REST_Response {
		$body       = $request->get_json_params();
		$repository = new \IMedia\Menu\Database\TemplateRepository();
		$id         = $repository->create( $body );

		if ( ! $id ) {
			return new \WP_REST_Response( array( 'error' => 'Failed to create template' ), 500 );
		}

		do_action( 'imedia_menu_template_saved', $id, $body );

		return new \WP_REST_Response( array( 'id' => $id ), 201 );
	}

	public function updateTemplate( \WP_REST_Request $request ): \WP_REST_Response {
		$id   = (int) $request->get_param( 'id' );
		$body = $request->get_json_params();

		$repository = new \IMedia\Menu\Database\TemplateRepository();
		$repository->update( $id, $body );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function deleteTemplate( \WP_REST_Request $request ): \WP_REST_Response {
		$id = (int) $request->get_param( 'id' );

		$repository = new \IMedia\Menu\Database\TemplateRepository();
		$repository->delete( $id );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function getRevisions( \WP_REST_Request $request ): \WP_REST_Response {
		$panelId = (int) $request->get_param( 'panel_id' );

		$repository = new \IMedia\Menu\Database\RevisionRepository();
		$revisions  = $repository->findByPanel( $panelId );

		return new \WP_REST_Response( $revisions, 200 );
	}

	public function restoreRevision( \WP_REST_Request $request ): \WP_REST_Response {
		$id = (int) $request->get_param( 'id' );

		$repository = new \IMedia\Menu\Database\RevisionRepository();
		$revision   = $repository->restore( $id );

		if ( ! $revision ) {
			return new \WP_REST_Response( array( 'error' => 'Revision not found' ), 404 );
		}

		$panelRepo = new \IMedia\Menu\Database\PanelRepository();
		$panel     = $panelRepo->findByMenuItem( (int) $revision->menu_item_id );

		if ( $panel ) {
			$panelRepo->save(
				(int) $revision->menu_item_id,
				(int) $panel->menu_id,
				array(
					'config' => $revision->config,
					'styles' => $revision->styles,
				)
			);
		}

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function getSettings(): \WP_REST_Response {
		$settings = get_option( 'imedia_menu_settings', array() );

		return new \WP_REST_Response( $settings, 200 );
	}

	public function updateSettings( \WP_REST_Request $request ): \WP_REST_Response {
		$body     = $request->get_json_params();
		$settings = get_option( 'imedia_menu_settings', array() );
		$settings = array_merge( $settings, $body );

		update_option( 'imedia_menu_settings', $settings );

		do_action( 'imedia_menu_settings_saved', $settings );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	public function flushCache(): \WP_REST_Response {
		$invalidator = new \IMedia\Menu\Cache\CacheInvalidator();
		$invalidator->invalidateAll();

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}
}
