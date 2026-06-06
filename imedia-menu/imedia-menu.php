<?php
/**
 * Plugin Name:       iMedia Menu
 * Plugin URI:        https://inventivemedia.com/imedia-menu
 * Description:       Premium-grade WordPress navigation and mega menu plugin. Mega menus, conditional visibility, performance-first, and WCAG 2.1 AA accessible.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Inventive Media
 * Author URI:        https://inventivemedia.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       imedia-menu
 * Domain Path:       /languages
 * Update URI:        https://inventivemedia.com/imedia-menu/update
 *
 * @package IMedia_Menu
 */

declare(strict_types=1);

namespace IMedia\Menu;

defined( 'ABSPATH' ) || exit;

const VERSION  = '1.0.0';
const FILE     = __FILE__;
const DIR      = __DIR__;
const URL      = plugin_dir_url( __FILE__ );
const BASENAME = plugin_basename( __FILE__ );
const MIN_PHP  = '8.1';
const MIN_WP   = '6.4';

if ( version_compare( PHP_VERSION, MIN_PHP, '<' ) ) {
	add_action(
		'admin_notices',
		function (): void {
			$message = sprintf(
				__( 'iMedia Menu requires PHP %1$s or higher. Your site is running PHP %2$s.', 'imedia-menu' ),
				MIN_PHP,
				PHP_VERSION
			);
			printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
		}
	);
	return;
}

if ( file_exists( DIR . '/vendor/autoload.php' ) ) {
	require_once DIR . '/vendor/autoload.php';
}

spl_autoload_register(
	function ( string $class ): void {
		$prefix = 'IMedia\\Menu\\';
		$base   = DIR . '/src/';

		if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$file     = $base . str_replace( '\\', '/', $relative ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

register_activation_hook( FILE, array( Activator::class, 'activate' ) );
register_deactivation_hook( FILE, array( Deactivator::class, 'deactivate' ) );

add_action(
	'init',
	function (): void {
		load_plugin_textdomain( 'imedia-menu', false, dirname( BASENAME ) . '/languages' );
	}
);

add_action( 'plugins_loaded', array( Plugin::class, 'init' ) );
