<?php

/**
 * WordPress function stubs for standalone PHPUnit testing.
 * Minimal implementations to satisfy type declarations in src code.
 */

define( 'ABSPATH', '/tmp/wordpress/' );
define( 'WP_CONTENT_DIR', '/tmp/wordpress/wp-content' );
define( 'WP_PLUGIN_DIR', '/tmp/wordpress/wp-content/plugins' );
define( 'IMEDIA_MENU_VERSION', '1.0.0' );
define( 'IMEDIA_MENU_DIR', '/tmp/wordpress/wp-content/plugins/imedia-menu' );
define( 'IMEDIA_MENU_URL', 'http://example.com/wp-content/plugins/imedia-menu' );
define( 'IMEDIA_MENU_BASENAME', 'imedia-menu/imedia-menu.php' );
define( 'DB_VERSION', 100 );
define( 'MIN_PHP_VERSION', '8.1' );
define( 'MIN_WP_VERSION', '6.4' );

if ( ! class_exists( 'Walker' ) ) {
	abstract class Walker {
		public $tree_type = array();
		public $db_fields = array();
		public function walk( $elements, $max_depth, ...$args ) {
			return ''; }
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( is_object( $args ) && isset( $args->before ) ) {
				$output .= $args->before;
			}
			if ( is_object( $args ) && isset( $args->link_before ) ) {
				$output .= $args->link_before;
			}
			$output .= '<' . 'li' . '>';
			$output .= '<span>' . ( is_object( $element ) && isset( $element->title ) ? $element->title : '' ) . '</span>';
			$output .= '</' . 'li' . '>';
			if ( is_object( $args ) && isset( $args->after ) ) {
				$output .= $args->after;
			}
		}
	}
}

if ( ! class_exists( 'Walker_Nav_Menu' ) ) {
	abstract class Walker_Nav_Menu extends \Walker {
		public $tree_type = array( 'post_type', 'taxonomy', 'custom' );
		public $db_fields = array(
			'parent' => 'menu_item_parent',
			'id'     => 'db_id',
		);

		public function start_lvl( &$output, $depth = 0, $args = null ) {}
		public function end_lvl( &$output, $depth = 0, $args = null ) {}
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {}
		public function end_el( &$output, $item, $depth = 0, $args = null ) {}
	}
}

function __( $text, $domain = 'default' ) {
	return $text; }
function esc_html__( $text, $domain = 'default' ) {
	return $text; }
function esc_attr__( $text, $domain = 'default' ) {
	return $text; }
function _x( $text, $context, $domain = 'default' ) {
	return $text; }
function esc_attr( $value ) {
	return $value === null || $value === '' ? '' : htmlspecialchars( (string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ); }
function sanitize_html_class( $class, $fallback = '' ) {
	if ( ! is_string( $class ) || $class === '' ) {
		return $fallback;
	}
	$safe = preg_replace( '/[^a-zA-Z0-9_\-]/', '', $class );
	if ( $safe === null || $safe === '' ) {
		return $fallback;
	}
	if ( (string) (int) $safe === $safe && $safe !== '' ) {
		return $fallback;
	}
	return $safe;
}
function esc_url( $url ) {
	if ( $url === null || $url === '' ) {
		return '';
	}
	$url   = (string) $url;
	$lower = strtolower( trim( $url ) );
	if ( str_starts_with( $lower, 'javascript:' ) || str_starts_with( $lower, 'data:' ) || str_starts_with( $lower, 'vbscript:' ) ) {
		return '';
	}
	return htmlspecialchars( $url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
}
function esc_url_raw( $url ) {
	if ( $url === null || $url === '' ) {
		return '';
	}
	$url   = (string) $url;
	$lower = strtolower( trim( $url ) );
	if ( str_starts_with( $lower, 'javascript:' ) || str_starts_with( $lower, 'data:' ) || str_starts_with( $lower, 'vbscript:' ) ) {
		return '';
	}
	return $url;
}
function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' ); }
function tag_escape( $tag ) {
	return strtolower( preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $tag ) ); }
function wp_kses_post( $data ) {
	return wp_kses( $data, wp_kses_allowed_html( 'post' ) ); }
function wp_kses_allowed_html( $context = 'post' ) {
	return array(
		'a'      => array(
			'href'   => true,
			'title'  => true,
			'target' => true,
			'rel'    => true,
			'class'  => true,
		),
		'p'      => array(
			'class' => true,
			'style' => true,
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		'u'      => array(),
		'span'   => array(
			'class' => true,
			'style' => true,
		),
		'div'    => array(
			'class' => true,
			'style' => true,
		),
		'img'    => array(
			'src'     => true,
			'alt'     => true,
			'class'   => true,
			'loading' => true,
			'width'   => true,
			'height'  => true,
		),
		'ul'     => array( 'class' => true ),
		'ol'     => array( 'class' => true ),
		'li'     => array( 'class' => true ),
		'h1'     => array(),
		'h2'     => array(),
		'h3'     => array(),
		'h4'     => array(),
		'h5'     => array(),
		'h6'     => array(),
	);
}
function wp_kses( $data, $allowed_html, $context = '' ) {
	if ( ! is_string( $data ) ) {
		return '';
	}
	if ( ! is_array( $allowed_html ) ) {
		return strip_tags( $data );
	}
	$allowed_tags = array_keys( $allowed_html );
	if ( empty( $allowed_tags ) ) {
		return strip_tags( $data );
	}
	$pattern = '@<(\/?)([a-zA-Z][a-zA-Z0-9]*)\b[^>]*>@';
	return preg_replace_callback(
		$pattern,
		function ( $m ) use ( $allowed_tags ) {
			$tag = strtolower( $m[2] );
			if ( in_array( $tag, $allowed_tags, true ) ) {
				return $m[0];
			}
			return '';
		},
		$data
	);
}
define( 'ARRAY_A', 'ARRAY_A' );
define( 'OBJECT', 'OBJECT' );
define( 'OBJECT_K', 'OBJECT_K' );
function is_user_logged_in() {
	return false; }
function wp_get_current_user() {
	return (object) array( 'roles' => array() ); }
function get_locale() {
	return 'en_US'; }
function wp_is_mobile() {
	return false; }
function is_singular() {
	return false; }
function is_front_page() {
	return false; }
function is_home() {
	return false; }
function is_archive() {
	return false; }
function is_search() {
	return false; }
function is_404() {
	return false; }
function get_queried_object_id() {
	return 0; }
function is_admin() {
	return false; }
function current_time( $type ) {
	return $type === 'timestamp' ? time() : date( 'Y-m-d H:i:s' ); }
function do_action( $hook, ...$args ) {
	if ( isset( $GLOBALS['__wp_actions'][ $hook ] ) ) {
		foreach ( $GLOBALS['__wp_actions'][ $hook ] as $cb ) {
			call_user_func_array( $cb, $args );
		}
	}
}
function apply_filters( $hook, $value, ...$args ) {
	if ( isset( $GLOBALS['__wp_filters'][ $hook ] ) ) {
		foreach ( $GLOBALS['__wp_filters'][ $hook ] as $cb ) {
			$value = call_user_func_array( $cb, array_merge( array( $value ), $args ) );
		}
	}
	return $value;
}
function add_action( $hook, $callback, $priority = 10, $acceptedArgs = 1 ) {
	$GLOBALS['__wp_actions'][ $hook ][] = $callback;
}
function add_filter( $hook, $callback, $priority = 10, $acceptedArgs = 1 ) {
	$GLOBALS['__wp_filters'][ $hook ][] = $callback;
}
function get_option( $option, $default = false ) {
	$opts = $GLOBALS['_imedia_menu_options'] ?? array();
	return $opts[ $option ] ?? $default;
}
function update_option( $option, $value, $autoload = false ) {
	$GLOBALS['_imedia_menu_options'][ $option ] = $value;
	return true;
}
function add_option( $option, $value, $deprecated = '', $autoload = 'yes' ) {
	update_option( $option, $value, $autoload );
	return true;
}
function delete_option( $option ) {
	return true; }
function get_post_meta( $postId, $key, $single = false ) {
	if ( isset( $GLOBALS['_post_meta'][ $postId ][ $key ] ) ) {
		$value = $GLOBALS['_post_meta'][ $postId ][ $key ];
		return $single ? $value : array( $value );
	}
	return $single ? '' : array();
}
function update_post_meta( $postId, $key, $value ) {
	$GLOBALS['_post_meta'][ $postId ][ $key ] = $value;
	return true;
}
function delete_post_meta( $postId, $key ) {
	unset( $GLOBALS['_post_meta'][ $postId ][ $key ] );
	return true;
}
function wp_json_encode( $data, $options = 0, $depth = 512 ) {
	return json_encode( $data, $options, $depth ) ?: ''; }
function wp_get_nav_menus() {
	return array(); }
function wp_get_nav_menu_items( $menuId ) {
	return false; }
function get_nav_menu_locations() {
	return $GLOBALS['_nav_menu_locations'] ?? array(); }
function get_registered_nav_menus() {
	return $GLOBALS['_wp_registered_nav_menus'] ?? array(); }
function wp_upload_bits( $name, $deprecated = null, $content = '', $time = null ) {
	return array(
		'file'  => '/tmp/' . $name,
		'url'   => '/tmp/' . $name,
		'error' => false,
	); }
function wp_insert_attachment( $args, $file = '', $parent = 0 ) {
	return 999; }
function wp_generate_attachment_metadata( $attachmentId, $file ) {
	return array(); }
function wp_get_attachment_url( $attachmentId ) {
	return false; }
function get_post_mime_type( $postId ) {
	return false; }
function get_attached_file( $attachmentId ) {
	return false; }
function wp_register_script( $handle, $src, $deps = array(), $ver = null, $args = array() ) {
	return true; }
function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = null, $args = array() ) {}
function wp_localize_script( $handle, $objectName, $data ) {}
function wp_register_style( $handle, $src, $deps = array(), $ver = null, $media = 'all' ) {
	return true; }
function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = null, $media = 'all' ) {}
function wp_add_inline_style( $handle, $data ) {
	return true; }
function register_nav_menus( $locations ) {}
function register_rest_route( $namespace, $route, $args ) {
	return true; }
function sanitize_text_field( $str ) {
	if ( ! is_string( $str ) ) {
		return ''; }
	$str = preg_replace( '/[\r\n\t\0\x0B]/', '', $str );
	$str = strip_tags( $str );
	return trim( $str );
}
function sanitize_key( $key ) {
	return preg_replace( '/[^a-zA-Z0-9_\-]/', '', $key ); }
function sanitize_title( $title ) {
	return strtolower( preg_replace( '/[^a-zA-Z0-9_\-]/', '', (string) $title ) ); }
function sanitize_title_with_dashes( $title ) {
	return strtolower( preg_replace( '/[\s_]+/', '-', preg_replace( '/[^a-zA-Z0-9_\-]/', '', (string) $title ) ) ); }
function has_nav_menu( $location ) {
	return ! empty( $GLOBALS['_nav_menu_locations'][ $location ] ?? null ); }
function maybe_unserialize( $data ) {
	if ( is_string( $data ) && ( $unserialized = @unserialize( $data ) ) !== false || $data === 'b:0;' ) {
		return $unserialized;
	}
	return $data;
}
function absint( $maybeint ) {
	return (int) $maybeint; }
function _doing_it_wrong( $function, $message, $version = null ) {}
function wp_remote_get( $url, $args = array() ) {
	return array(); }
function wp_remote_retrieve_body( $response ) {
	return ''; }
function wp_safe_remote_get( $url, $args = array() ) {
	return array(); }
function wp_safe_remote_post( $url, $args = array() ) {
	return array(); }
function register_block_type( $name, $args = array() ) {
	return true; }
function add_shortcode( $tag, $callback ) {}
function shortcode_exists( $tag ) {
	return false; }
function wp_enqueue_block_style( $name, $args ) {
	return true; }
function wp_set_script_translations( $handle, $domain = 'default', $path = '' ) {}
function get_transient( $key ) {
	return false; }
function set_transient( $key, $value, $expiration = 0 ) {
	return true; }
function delete_transient( $key ) {
	return true; }
function wp_cache_get( $key, $group = '', $force = false, &$found = null ) {
	$found = false;
	return false; }
function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
	return true; }
function wp_cache_delete( $key, $group = '' ) {
	return true; }
function wp_cache_flush() {
	return true; }
function wp_cache_flush_group( $group ) {
	$GLOBALS['__wp_cache_flush_group_calls'][] = $group;
	return true;
}
function wp_clean_plugins_cache( $clearMu = false ) {}
function wp_using_ext_object_cache() {
	return false; }
function clean_post_cache( $postId ) {}
function wp_defer_term_counting( $defer ) {}
function wp_schedule_single_event( $timestamp, $hook, $args = array() ) {
	return true; }
function wp_next_scheduled( $hook, $args = array() ) {
	return false; }
function wp_clear_scheduled_hook( $hook, $args = array() ) {}

function register_setting( $optionGroup, $optionName, $args = array() ) {
	return true; }
function get_search_form( $echo = true ) {
	$form = '<form role="search" method="get" class="search-form" action="http://example.com/">
        <label><span class="screen-reader-text">Search for:</span>
        <input type="search" class="search-field" placeholder="Search..." value="" name="s" /></label>
        <button type="submit" class="search-submit">Search</button>
    </form>';
	if ( $echo ) {
		echo $form;
		return ''; }
	return $form;
}
function home_url( $path = '' ) {
	return 'http://example.com/' . ltrim( $path, '/' ); }
function get_search_query() {
	return ''; }
function do_shortcode( $content, $ignoreHtml = false ) {
	return $content; }
function has_filter( $hook ) {
	return ! empty( $GLOBALS['__wp_filters'][ $hook ] ); }
function has_action( $hook ) {
	return ! empty( $GLOBALS['__wp_actions'][ $hook ] ); }
function remove_action( $hook, $callback, $priority = 10 ) {
	return true; }
function remove_filter( $hook, $callback, $priority = 10 ) {
	if ( isset( $GLOBALS['__wp_filters'][ $hook ] ) ) {
		$GLOBALS['__wp_filters'][ $hook ] = array_filter(
			$GLOBALS['__wp_filters'][ $hook ],
			function ( $cb ) use ( $callback ) {
				return $cb !== $callback;
			}
		);
	}
	return true;
}
function wp_dequeue_script( $handle ) {}
function wp_dequeue_style( $handle ) {}
function wp_deregister_script( $handle ) {}
function wp_deregister_style( $handle ) {}
function wp_style_is( $handle, $status = 'enqueued' ) {
	return false; }
function wp_script_is( $handle, $status = 'enqueued' ) {
	return false; }
function wp_add_inline_script( $handle, $data, $position = 'after' ) {
	return true; }
function load_plugin_textdomain( $domain, $deprecated = false, $pluginRelPath = '' ) {}
function register_activation_hook( $file, $callback ) {}
function register_deactivation_hook( $file, $callback ) {}
function plugin_dir_path( $file ) {
	return '/tmp/plugins/imedia-menu/'; }
function plugin_dir_url( $file ) {
	return 'http://example.com/wp-content/plugins/imedia-menu/'; }
function plugin_basename( $file ) {
	return 'imedia-menu/imedia-menu.php'; }
function wp_upload_dir() {
	return array(
		'path'    => '/tmp/wordpress/wp-content/uploads',
		'url'     => 'http://example.com/wp-content/uploads',
		'subdir'  => '',
		'basedir' => '/tmp/wordpress/wp-content/uploads',
		'baseurl' => 'http://example.com/wp-content/uploads',
		'error'   => false,
	);
}
function wp_mkdir_p( $path ) {
	return true; }
function trailingslashit( $string ) {
	return rtrim( $string, '/\\' ) . '/'; }
function untrailingslashit( $string ) {
	return rtrim( $string, '/\\' ); }
function wp_normalize_path( $path ) {
	return str_replace( '\\', '/', $path ); }
function add_menu_page( $pageTitle, $menuTitle, $capability, $menuSlug, $function = '', $iconUrl = '', $position = null ) {
	return $menuSlug; }
function add_submenu_page( $parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $function = '' ) {
	return $menuSlug; }
function add_options_page( $pageTitle, $menuTitle, $capability, $menuSlug, $function = '' ) {
	return $menuSlug; }
function current_user_can( $capability, ...$args ) {
	return true; }
function wp_die( $message = '', $title = '', $args = array() ) {}
function did_action( $hook ) {
	return 0; }
function doing_action( $hook ) {
	return false; }
function wp_installing() {
	return false; }
function dbDelta( $queries = '', $execute = true ) {
	return array(); }
function get_bloginfo( $show = '' ) {
	return 'Test Site'; }
function get_template(): string {
	return $GLOBALS['__active_theme_template'] ?? 'twentytwentyfour'; }
function wp_nonce_url( $actionurl, $action = '', $name = '_wpnonce' ) {
	return $actionurl; }
function wp_create_nonce( $action = -1 ) {
	return 'test_nonce'; }
function wp_verify_nonce( $nonce, $action = -1 ) {
	return 1; }
function check_ajax_referer( $action = -1, $queryArg = false, $die = true ) {
	return true; }
function wp_is_post_revision( $postId ) {
	return false; }
function wp_is_post_autosave( $postId ) {
	return false; }
function sanitize_textarea_field( $str ) {
	return sanitize_text_field( $str ?? '' ); }
function wp_unslash( $value ) {
	return $value; }
function admin_url( $path = '', $scheme = 'admin' ) {
	return 'http://example.com/wp-admin/' . ltrim( $path, '/' ); }
function add_meta_box( $id, $title, $callback, $screen, $context = 'advanced', $priority = 'default', $callbackArgs = array() ) {}
function wp_nav_menu( $args = array() ) {}
function register_widget( $widgetClass ) {}
function register_post_type( $postType, $args = array() ) {}
function register_taxonomy( $taxonomy, $objectType, $args = array() ) {}
function wp_register_sidebar_widget( $id, $name, $outputCallback, $options = array() ) {}
function the_widget( $widget, $instance = array(), $args = array() ) {}
function is_active_widget( $callback = false, $widgetId = false, $idBase = false, $skipInactive = true ) {
	return false; }
function get_user_by( $field, $value ) {
	return false; }
function get_avatar( $idOrEmail, $size = 96, $default = '', $alt = '', $args = array() ) {
	return ''; }
function date_i18n( $format, $timestamp = null, $gmt = false ) {
	return date( $format, $timestamp ?? time() ); }
function wp_remote_post( $url, $args = array() ) {
	return array(
		'body'     => '',
		'response' => array( 'code' => 200 ),
	); }
function wp_generate_uuid4() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0x0fff ) | 0x4000, mt_rand( 0, 0x3fff ) | 0x8000, mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) ); }
function WC() {
	if ( isset( $GLOBALS['__wc_active'] ) && $GLOBALS['__wc_active'] === false ) {
		return null;
	}
	return new class() {
		public $cart;
		public function __construct() {
			$this->cart = new class() {
				public function get_cart_contents_count() {
					return (int) ( $GLOBALS['__wc_count'] ?? 0 ); }
				public function get_cart_subtotal() {
					return (string) ( $GLOBALS['__wc_total'] ?? '0.00' ); }
				public function get_cart() {
					return (array) ( $GLOBALS['__wc_items'] ?? array() ); }
			};
		}
	};
}

class wpdb {

	public $prefix             = 'wp_';
	public $posts              = 'wp_posts';
	public $postmeta           = 'wp_postmeta';
	public $options            = 'wp_options';
	public $usermeta           = 'wp_usermeta';
	public $users              = 'wp_users';
	public $terms              = 'wp_terms';
	public $term_taxonomy      = 'wp_term_taxonomy';
	public $term_relationships = 'wp_term_relationships';
	public $commentmeta        = 'wp_commentmeta';

	public function prepare( $query, ...$args ) {
		return $query; }
	public function get_row( $query, $output = OBJECT, $y = 0 ) {
		return null; }
	public function get_results( $query, $output = OBJECT ) {
		return array(); }
	public function get_var( $query = null, $x = 0, $y = 0 ) {
		return null; }
	public function insert( $table, $data, $format = null ) {
		return 1; }
	public function update( $table, $data, $where, $format = null, $whereFormat = null ) {
		return 1; }
	public function delete( $table, $where, $whereFormat = null ) {
		return 1; }
	public function query( $query ) {
		return true; }
	public function escape( $data ) {
		return addslashes( $data ); }
	public function esc_like( $data ) {
		return addslashes( $data ); }
	public function _real_escape( $data ) {
		return addslashes( $data ); }
	public function _escape( $data ) {
		return addslashes( $data ); }
	public function check_database_version() {}
	public function supports_collation() {}
	public function has_cap( $dbCap ) {
		return true; }
	public function db_version( $allowCache = true ) {
		return '8.0.35'; }
	public function get_charset_collate() {
		return 'utf8mb4_unicode_ci'; }
}

$GLOBALS['wpdb'] = new wpdb();

// --- Integration stubs ---

function get_current_user_id(): int {
	return 1;
}

function get_user_meta( int $userId, string $key, bool $single = false ) {
	$meta = $GLOBALS['__user_meta'][ $userId ] ?? array();
	if ( $single ) {
		return $meta[ $key ] ?? '';
	}
	return array( $meta[ $key ] ?? '' );
}

function update_user_meta( int $userId, string $key, $value ): bool {
	$GLOBALS['__user_meta'][ $userId ][ $key ] = $value;
	return true;
}

// --- WPML stubs ---

if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
	define( 'ICL_SITEPRESS_VERSION', '4.6.0' );
}

if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
	define( 'ICL_LANGUAGE_CODE', 'en' );
}

if ( ! function_exists( 'wpml_get_language_information' ) ) {
	function wpml_get_language_information( $post = null ): array {
		return $GLOBALS['__wpml_info'] ?? array( 'locale' => 'en_US' );
	}
}

if ( ! function_exists( 'wp_get_nav_menu_object' ) ) {
	function wp_get_nav_menu_object( int $menuId ) {
		$menus = $GLOBALS['__nav_menus'] ?? array();
		return $menus[ $menuId ] ?? false;
	}
}

// --- Polylang stubs ---

if ( ! function_exists( 'pll_default_language' ) ) {
	function pll_default_language( $field = 'slug' ): string {
		return 'en';
	}
}

if ( ! function_exists( 'pll_current_language' ) ) {
	function pll_current_language( $field = 'slug' ) {
		if ( isset( $GLOBALS['__pll_current'] ) ) {
			return $GLOBALS['__pll_current'];
		}
		return $field === 'locale' ? 'en_US' : 'en';
	}
}

if ( ! function_exists( 'pll_languages_list' ) ) {
	function pll_languages_list(): array {
		return $GLOBALS['__pll_languages'] ?? array( 'en' );
	}
}

// --- TranslatePress stubs ---

if ( ! function_exists( 'trp_get_language' ) ) {
	function trp_get_language(): string {
		return $GLOBALS['__trp_lang'] ?? 'en_US';
	}
}

if ( ! function_exists( 'trp_get_languages' ) ) {
	function trp_get_languages(): array {
		return $GLOBALS['__trp_languages'] ?? array( 'en_US' => 'English' );
	}
}

if ( ! class_exists( 'WP_REST_Request' ) ) {
	class WP_REST_Request {
		private string $method;
		private string $route;
		private array $params     = array();
		private ?string $body     = null;
		private array $jsonParams = array();

		public function __construct( string $method = 'GET', string $route = '' ) {
			$this->method = strtoupper( $method );
			$this->route  = $route;
		}

		public function get_method(): string {
			return $this->method;
		}

		public function get_route(): string {
			return $this->route;
		}

		public function get_param( string $key ) {
			return $this->params[ $key ] ?? null;
		}

		public function set_param( string $key, $value ): void {
			$this->params[ $key ] = $value;
		}

		public function get_params(): array {
			return $this->params;
		}

		public function get_body(): string {
			return $this->body ?? '';
		}

		public function set_body( string $body ): void {
			$this->body = $body;
			$decoded    = json_decode( $body, true );
			if ( is_array( $decoded ) ) {
				$this->jsonParams = $decoded;
			}
		}

		public function get_json_params(): array {
			return $this->jsonParams;
		}
	}
}

if ( ! class_exists( 'WP_REST_Response' ) ) {
	class WP_REST_Response {
		private $data;
		private int $status;

		public function __construct( $data = null, int $status = 200 ) {
			$this->data   = $data;
			$this->status = $status;
		}

		public function get_data() {
			return $this->data;
		}

		public function get_status(): int {
			return $this->status;
		}

		public function set_data( $data ): void {
			$this->data = $data;
		}

		public function set_status( int $status ): void {
			$this->status = $status;
		}
	}
}

if ( ! class_exists( 'WP_REST_Server' ) ) {
	class WP_REST_Server {
		const READABLE  = 'GET';
		const CREATABLE = 'POST';
		const EDITABLE  = 'POST, PUT, PATCH';
		const DELETABLE = 'DELETE';
	}
}

// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter, WordPress.Arrays.ArrayDeclarationSpacing
function wp_send_json_success( $data = null, ?int $statusCode = null ) {
	wp_die( wp_json_encode( array( 'success' => true, 'data' => $data ) ) );
}

function wp_send_json_error( $data = null, ?int $statusCode = null ) {
	wp_die( wp_json_encode( array( 'success' => false, 'data' => $data ) ) );
}
// phpcs:enable
