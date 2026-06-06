<?php

/**
 * WordPress function stubs for standalone PHPUnit testing.
 * Minimal implementations to satisfy type declarations in src code.
 */

define('ABSPATH', '/tmp/wordpress/');
define('WP_CONTENT_DIR', '/tmp/wordpress/wp-content');
define('WP_PLUGIN_DIR', '/tmp/wordpress/wp-content/plugins');
define('IMEDIA_MENU_VERSION', '1.0.0');
define('DB_VERSION', 100);
define('MIN_PHP_VERSION', '8.1');
define('MIN_WP_VERSION', '6.4');

function __($text, $domain = 'default') { return $text; }
function esc_html__($text, $domain = 'default') { return $text; }
function esc_attr__($text, $domain = 'default') { return $text; }
function _x($text, $context, $domain = 'default') { return $text; }
function esc_attr($value) { return $value === null || $value === '' ? '' : htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function esc_url($url) { return $url; }
function esc_html($text) { return htmlspecialchars((string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function tag_escape($tag) { return strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $tag)); }
function wp_kses_post($data) { return $data; }
function wp_kses_allowed_html($context = 'post') { return []; }
function wp_kses($data, $allowed_html, $context = '') { return $data; }
define('ARRAY_A', 'ARRAY_A');
define('OBJECT', 'OBJECT');
define('OBJECT_K', 'OBJECT_K');
function is_user_logged_in() { return false; }
function wp_get_current_user() { return (object) ['roles' => []]; }
function get_locale() { return 'en_US'; }
function wp_is_mobile() { return false; }
function is_singular() { return false; }
function is_front_page() { return false; }
function is_home() { return false; }
function is_archive() { return false; }
function is_search() { return false; }
function is_404() { return false; }
function get_queried_object_id() { return 0; }
function is_admin() { return false; }
function current_time($type) { return $type === 'timestamp' ? time() : date('Y-m-d H:i:s'); }
function do_action($hook, ...$args) {}
function apply_filters($hook, $value, ...$args) { return $value; }
function add_action($hook, $callback, $priority = 10, $acceptedArgs = 1) {}
function add_filter($hook, $callback, $priority = 10, $acceptedArgs = 1) {}
function get_option($option, $default = false) { return $default; }
function update_option($option, $value, $autoload = false) { return true; }
function delete_option($option) { return true; }
function get_post_meta($postId, $key, $single = false) { return $single ? '' : []; }
function update_post_meta($postId, $key, $value) { return true; }
function delete_post_meta($postId, $key) { return true; }
function wp_json_encode($data, $options = 0, $depth = 512) { return json_encode($data, $options, $depth) ?: ''; }
function wp_get_nav_menus() { return []; }
function wp_get_nav_menu_items($menuId) { return false; }
function get_nav_menu_locations() { return []; }
function wp_upload_bits($name, $deprecated = null, $content = '', $time = null) { return ['file' => '/tmp/' . $name, 'url' => '/tmp/' . $name, 'error' => false]; }
function wp_insert_attachment($args, $file = '', $parent = 0) { return 999; }
function wp_generate_attachment_metadata($attachmentId, $file) { return []; }
function wp_get_attachment_url($attachmentId) { return false; }
function get_post_mime_type($postId) { return false; }
function get_attached_file($attachmentId) { return false; }
function wp_register_script($handle, $src, $deps = [], $ver = null, $args = []) { return true; }
function wp_enqueue_script($handle, $src = '', $deps = [], $ver = null, $args = []) {}
function wp_localize_script($handle, $objectName, $data) {}
function wp_register_style($handle, $src, $deps = [], $ver = null, $media = 'all') { return true; }
function wp_enqueue_style($handle, $src = '', $deps = [], $ver = null, $media = 'all') {}
function wp_add_inline_style($handle, $data) { return true; }
function register_nav_menus($locations) {}
function register_rest_route($namespace, $route, $args) { return true; }
function sanitize_text_field($str) { return $str; }
function sanitize_key($key) { return preg_replace('/[^a-zA-Z0-9_\-]/', '', $key); }
function absint($maybeint) { return (int) $maybeint; }
function _doing_it_wrong($function, $message, $version = null) {}
function wp_remote_get($url, $args = []) { return []; }
function wp_remote_retrieve_body($response) { return ''; }
function wp_safe_remote_get($url, $args = []) { return []; }
function wp_safe_remote_post($url, $args = []) { return []; }
function register_block_type($name, $args = []) { return true; }
function add_shortcode($tag, $callback) {}
function shortcode_exists($tag) { return false; }
function wp_enqueue_block_style($name, $args) { return true; }
function wp_set_script_translations($handle, $domain = 'default', $path = '') {}
function get_transient($key) { return false; }
function set_transient($key, $value, $expiration = 0) { return true; }
function delete_transient($key) { return true; }
function wp_cache_get($key, $group = '', $force = false, &$found = null) { $found = false; return false; }
function wp_cache_set($key, $data, $group = '', $expire = 0) { return true; }
function wp_cache_delete($key, $group = '') { return true; }
function wp_cache_flush() { return true; }
function wp_cache_flush_group($group) {
    $GLOBALS['__wp_cache_flush_group_calls'][] = $group;
    return true;
}
function wp_clean_plugins_cache($clearMu = false) {}
function wp_using_ext_object_cache() { return false; }
function clean_post_cache($postId) {}
function wp_defer_term_counting($defer) {}
function wp_schedule_single_event($timestamp, $hook, $args = []) { return true; }
function wp_next_scheduled($hook, $args = []) { return false; }
function wp_clear_scheduled_hook($hook, $args = []) {}

function get_search_form($echo = true) {
    $form = '<form role="search" method="get" class="search-form" action="http://example.com/">
        <label><span class="screen-reader-text">Search for:</span>
        <input type="search" class="search-field" placeholder="Search..." value="" name="s" /></label>
        <button type="submit" class="search-submit">Search</button>
    </form>';
    if ($echo) { echo $form; return ''; }
    return $form;
}
function home_url($path = '') { return 'http://example.com/' . ltrim($path, '/'); }
function get_search_query() { return ''; }
function do_shortcode($content, $ignoreHtml = false) { return $content; }
function has_filter($hook) { return false; }
function has_action($hook) { return false; }
function remove_action($hook, $callback, $priority = 10) { return true; }
function remove_filter($hook, $callback, $priority = 10) { return true; }
function wp_dequeue_script($handle) {}
function wp_dequeue_style($handle) {}
function wp_deregister_script($handle) {}
function wp_deregister_style($handle) {}
function wp_style_is($handle, $status = 'enqueued') { return false; }
function wp_script_is($handle, $status = 'enqueued') { return false; }
function wp_add_inline_script($handle, $data, $position = 'after') { return true; }
function load_plugin_textdomain($domain, $deprecated = false, $pluginRelPath = '') {}
function register_activation_hook($file, $callback) {}
function register_deactivation_hook($file, $callback) {}
function plugin_dir_path($file) { return '/tmp/plugins/imedia-menu/'; }
function plugin_dir_url($file) { return 'http://example.com/wp-content/plugins/imedia-menu/'; }
function plugin_basename($file) { return 'imedia-menu/imedia-menu.php'; }
function wp_upload_dir() {
    return [
        'path'    => '/tmp/wordpress/wp-content/uploads',
        'url'     => 'http://example.com/wp-content/uploads',
        'subdir'  => '',
        'basedir' => '/tmp/wordpress/wp-content/uploads',
        'baseurl' => 'http://example.com/wp-content/uploads',
        'error'   => false,
    ];
}
function wp_mkdir_p($path) { return true; }
function trailingslashit($string) { return rtrim($string, '/\\') . '/'; }
function untrailingslashit($string) { return rtrim($string, '/\\'); }
function wp_normalize_path($path) { return str_replace('\\', '/', $path); }
function add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, $function = '', $iconUrl = '', $position = null) { return $menuSlug; }
function add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $function = '') { return $menuSlug; }
function add_options_page($pageTitle, $menuTitle, $capability, $menuSlug, $function = '') { return $menuSlug; }
function current_user_can($capability, ...$args) { return true; }
function wp_die($message = '', $title = '', $args = []) {}
function did_action($hook) { return 0; }
function doing_action($hook) { return false; }
function wp_installing() { return false; }
function dbDelta($queries = '', $execute = true) { return []; }
function get_bloginfo($show = '') { return 'Test Site'; }
function wp_nonce_url($actionurl, $action = '', $name = '_wpnonce') { return $actionurl; }
function wp_create_nonce($action = -1) { return 'test_nonce'; }
function wp_verify_nonce($nonce, $action = -1) { return 1; }
function admin_url($path = '', $scheme = 'admin') { return 'http://example.com/wp-admin/' . ltrim($path, '/'); }
function add_meta_box($id, $title, $callback, $screen, $context = 'advanced', $priority = 'default', $callbackArgs = []) {}
function wp_nav_menu($args = []) {}
function register_widget($widgetClass) {}
function register_post_type($postType, $args = []) {}
function register_taxonomy($taxonomy, $objectType, $args = []) {}
function wp_register_sidebar_widget($id, $name, $outputCallback, $options = []) {}
function the_widget($widget, $instance = [], $args = []) {}

class wpdb
{
    public $prefix = 'wp_';
    public $posts = 'wp_posts';
    public $postmeta = 'wp_postmeta';
    public $options = 'wp_options';
    public $usermeta = 'wp_usermeta';
    public $users = 'wp_users';
    public $terms = 'wp_terms';
    public $term_taxonomy = 'wp_term_taxonomy';
    public $term_relationships = 'wp_term_relationships';
    public $commentmeta = 'wp_commentmeta';

    public function prepare($query, ...$args) { return $query; }
    public function get_row($query, $output = OBJECT, $y = 0) { return null; }
    public function get_results($query, $output = OBJECT) { return []; }
    public function get_var($query = null, $x = 0, $y = 0) { return null; }
    public function insert($table, $data, $format = null) { return 1; }
    public function update($table, $data, $where, $format = null, $whereFormat = null) { return 1; }
    public function delete($table, $where, $whereFormat = null) { return 1; }
    public function query($query) { return true; }
    public function escape($data) { return addslashes($data); }
    public function esc_like($data) { return addslashes($data); }
    public function _real_escape($data) { return addslashes($data); }
    public function _escape($data) { return addslashes($data); }
    public function check_database_version() {}
    public function supports_collation() {}
    public function has_cap($dbCap) { return true; }
    public function db_version($allowCache = true) { return '8.0.35'; }
    public function get_charset_collate() { return 'utf8mb4_unicode_ci'; }
}

$GLOBALS['wpdb'] = new wpdb();
