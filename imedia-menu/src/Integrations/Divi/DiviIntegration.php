<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Divi;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Integrations\AdminNotice;

final class DiviIntegration implements ServiceProvider {
	use AdminNotice;

	public function register(): void {}

	public function boot(): void {
		if ( ! $this->isDiviActive() ) {
			return;
		}

		add_filter( 'divi_module_library_modules_dependency_tree', array( $this, 'registerModule' ) );
		add_action( 'rest_api_init', array( $this, 'registerRestRoute' ) );
		add_action( 'divi_visual_builder_assets_before_enqueue_scripts', array( $this, 'enqueueVisualBuilderAssets' ) );

		$this->registerNotice(
			'divi',
			'<p>' . __( 'To display the iMedia Menu in a Divi layout, use the "iMedia Menu Location" module in the Divi Builder.', 'imedia-menu' )
			. ' <a href="https://www.elegantthemes.com/documentation/divi/" target="_blank">' . __( 'Learn more about Divi.', 'imedia-menu' ) . '</a></p>'
		);
		$this->enqueueDismissScript();

		add_action( 'admin_enqueue_scripts', array( $this, 'adminStyles' ) );
	}

	public function registerModule( array $modules ): array {
		$modules[] = __DIR__ . '/location/Module.php';
		return $modules;
	}

	public function registerRestRoute(): void {
		register_rest_route(
			'imedia-menu/v1',
			'/render-menu',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'renderMenuPreview' ),
				'permission_callback' => function (): bool {
					return current_user_can( 'edit_theme_options' );
				},
				'args'                => array(
					'location' => array(
						'required'          => true,
						'validate_callback' => function ( $param ): bool {
							return is_string( $param ) && sanitize_title( $param ) === $param;
						},
					),
				),
			)
		);
	}

	public function renderMenuPreview( \WP_REST_Request $request ): \WP_REST_Response {
		$location = sanitize_title( $request->get_param( 'location' ) );
		$html     = wp_nav_menu(
			array(
				'theme_location' => $location,
				'echo'           => false,
				'fallback_cb'    => '__return_false',
			)
		);

		return new \WP_REST_Response( array( 'html' => $html ), 200 );
	}

	public function enqueueVisualBuilderAssets(): void {
		$assetPath = IMEDIA_MENU_DIR . '/assets/admin/divi/build/index.asset.php';
		if ( ! file_exists( $assetPath ) ) {
			return;
		}
		$asset = require $assetPath;
		wp_enqueue_script(
			'imm-divi-visual-builder',
			IMEDIA_MENU_URL . 'assets/admin/divi/build/index.js',
			$asset['dependencies'] ?? array(),
			$asset['version'] ?? IMEDIA_MENU_VERSION,
			true
		);
	}

	public function adminStyles( string $hook ): void {
		if ( ! str_contains( $hook, 'imedia-menu' ) ) {
			return;
		}
		wp_add_inline_style( 'wp-admin', $this->shimmerCss() );
	}

	private function shimmerCss(): string {
		return 'tr.imm-divi-option { background: #f0f6fc; animation: imm-shimmer 2s ease-in-out infinite; }
			@keyframes imm-shimmer { 0%, 100% { background: #f0f6fc; } 50% { background: #e5f0fa; } }';
	}

	private function isDiviActive(): bool {
		$template = get_template();
		return $template === 'Divi' || $template === 'divi' || defined( 'ET_BUILDER_PRODUCT_VERSION' );
	}
}
