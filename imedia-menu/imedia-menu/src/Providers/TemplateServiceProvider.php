<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;

final class TemplateServiceProvider implements ServiceProvider {

	public function register(): void {
	}

	public function boot(): void {
	}

	public function locateTemplate( string $templateName ): string {
		$templateName = ltrim( $templateName, '/' );

		$child  = sprintf( '%s/imedia-menu/%s', get_stylesheet_directory(), $templateName );
		$parent = sprintf( '%s/imedia-menu/%s', get_template_directory(), $templateName );
		$plugin = IMEDIA_MENU_DIR . '/src/Templates/' . $templateName;

		if ( file_exists( $child ) ) {
			return $child;
		}

		if ( file_exists( $parent ) ) {
			return $parent;
		}

		return $plugin;
	}

	public function render( string $templateName, array $args = array() ): string {
		$path = $this->locateTemplate( $templateName . '.php' );

		if ( ! file_exists( $path ) ) {
			return sprintf( '<!-- Template not found: %s -->', esc_html( $templateName ) );
		}

		$path = apply_filters( 'imedia_menu_template_path', $path, $templateName );
		$args = apply_filters( 'imedia_menu_template_args', $args, $templateName );

		ob_start();
		extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		require $path;

		return (string) ob_get_clean();
	}
}
