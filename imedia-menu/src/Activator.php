<?php

declare(strict_types=1);

namespace IMedia\Menu;

use IMedia\Menu\Database\Schema;

final class Activator {

	public static function activate(): void {
		self::checkRequirements();
		self::createTables();
		self::setDefaults();
		flush_rewrite_rules();
		set_transient( 'imedia_menu_activated', true, 30 );
	}

	private static function checkRequirements(): void {
		if ( version_compare( PHP_VERSION, MIN_PHP, '<' ) ) {
			deactivate_plugins( IMEDIA_MENU_BASENAME );
			wp_die(
				esc_html__( 'iMedia Menu requires PHP 8.1 or higher.', 'imedia-menu' ),
				esc_html__( 'Plugin Activation Error', 'imedia-menu' ),
				array( 'back_link' => true )
			);
		}

		global $wp_version;
		if ( version_compare( $wp_version, MIN_WP, '<' ) ) {
			deactivate_plugins( IMEDIA_MENU_BASENAME );
			wp_die(
				esc_html__( 'iMedia Menu requires WordPress 6.4 or higher.', 'imedia-menu' ),
				esc_html__( 'Plugin Activation Error', 'imedia-menu' ),
				array( 'back_link' => true )
			);
		}

		$schema = new Schema();
		$schema->validateRequirements();
	}

	private static function createTables(): void {
		$schema = new Schema();
		$schema->create();
		update_option( 'imedia_menu_db_version', IMEDIA_MENU_VERSION );
	}

	private static function setDefaults(): void {
		if ( get_option( 'imedia_menu_settings' ) === false ) {
			add_option(
				'imedia_menu_settings',
				array(
					'trigger_type'             => 'hover',
					'hover_delay'              => 200,
					'default_animation'        => 'fade',
					'animation_duration'       => 200,
					'mobile_breakpoint'        => 768,
					'off_canvas_direction'     => 'right',
					'hamburger_style'          => 'classic',
					'enable_caching'           => true,
					'cache_duration'           => 60,
					'code_splitting'           => true,
					'delete_data_on_uninstall' => false,
				)
			);
		}

		if ( get_option( 'imedia_menu_version' ) === false ) {
			add_option( 'imedia_menu_version', IMEDIA_MENU_VERSION );
		}
	}
}
