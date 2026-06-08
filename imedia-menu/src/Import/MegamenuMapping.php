<?php

declare(strict_types=1);

namespace IMedia\Menu\Import;

final class MegamenuMapping {

	public static function mapSettings( array $megamenuSettings ): array {
		$mapped = array();

		$mapped['enabled']                     = true;
		$mapped['trigger_type']                = 'hover';
		$mapped['hover_delay']                 = 200;
		$mapped['default_animation']           = 'fade';
		$mapped['animation_duration']          = 200;
		$mapped['admin_bar_preview']           = true;
		$mapped['enable_caching']              = true;
		$mapped['cache_duration']              = 60;
		$mapped['code_splitting']              = true;
		$mapped['delete_data_on_uninstall']    = false;
		$mapped['dark_mode_enabled']           = false;
		$mapped['animation_easing']            = 'ease';
		$mapped['reduced_motion']              = true;
		$mapped['visibility_default_behavior'] = 'show_all';
		$mapped['locale_detection_method']     = 'auto';

		$mapped['mobile_breakpoint']    = 768;
		$mapped['off_canvas_direction'] = 'right';
		$mapped['hamburger_style']      = 'classic';

		$mapped['icon_providers'] = array(
			'dashicons'   => true,
			'fontawesome' => false,
			'custom_svg'  => false,
		);

		return $mapped;
	}

	public static function mergeLocationSettings( array $locationSettings ): array {
		$mapped = array();

		if ( isset( $locationSettings['sticky_enabled'] ) ) {
			$mapped['sticky'] = (bool) $locationSettings['sticky_enabled'];
		}

		if ( isset( $locationSettings['sticky_offset'] ) ) {
			$offset                  = (string) $locationSettings['sticky_offset'];
			$mapped['sticky_offset'] = absint( $offset );
		}

		return $mapped;
	}

	public static function mapMenuItemMeta( array $megameta ): array {
		$meta = array();

		if ( isset( $megameta['type'] ) ) {
			$meta['_imedia_menu_mega_enabled'] = $megameta['type'] === 'megamenu' ? '1' : '0';
		}

		if ( isset( $megameta['icon'] ) ) {
			$meta['_imedia_menu_icon'] = sanitize_text_field( $megameta['icon'] );
		}

		if ( isset( $megameta['hide_on_mobile'] ) ) {
			$meta['_imedia_menu_hide_on_mobile'] = $megameta['hide_on_mobile'] ? '1' : '0';
		}

		if ( isset( $megameta['hide_on_desktop'] ) ) {
			$meta['_imedia_menu_hide_on_desktop'] = $megameta['hide_on_desktop'] ? '1' : '0';
		}

		if ( isset( $megameta['disable_link'] ) ) {
			$meta['_imedia_menu_disable_link'] = $megameta['disable_link'] ? '1' : '0';
		}

		if ( isset( $megameta['hide_arrow'] ) ) {
			$meta['_imedia_menu_hide_arrow'] = $megameta['hide_arrow'] ? '1' : '0';
		}

		if ( isset( $megameta['hide_text'] ) ) {
			$meta['_imedia_menu_hide_text'] = $megameta['hide_text'] ? '1' : '0';
		}

		if ( isset( $megameta['description'] ) ) {
			$meta['_imedia_menu_description'] = sanitize_text_field( $megameta['description'] );
		}

		if ( isset( $megameta['badge'] ) && is_array( $megameta['badge'] ) ) {
			$meta['_imedia_menu_badge_text']       = sanitize_text_field( $megameta['badge']['text'] ?? '' );
			$meta['_imedia_menu_badge_color']      = sanitize_text_field( $megameta['badge']['background'] ?? '' );
			$meta['_imedia_menu_badge_text_color'] = sanitize_text_field( $megameta['badge']['text_color'] ?? '' );
			if ( isset( $megameta['badge']['style'] ) ) {
				$meta['_imedia_menu_badge_position'] = sanitize_text_field( $megameta['badge']['style'] );
			}
		}

		if ( isset( $megameta['roles'] ) && is_array( $megameta['roles'] ) ) {
			$condition = array(
				'type'  => 'user_role',
				'roles' => $megameta['roles']['roles'] ?? array(),
			);
			if ( isset( $megameta['roles']['display_mode'] ) ) {
				if ( $megameta['roles']['display_mode'] === 'logged_in' ) {
					$condition['type']  = 'login_state';
					$condition['state'] = 'in';
				} elseif ( $megameta['roles']['display_mode'] === 'logged_out' ) {
					$condition['type']  = 'login_state';
					$condition['state'] = 'out';
				}
			}
			$meta['_imedia_menu_visibility'] = wp_json_encode( $condition );
		}

		$meta['_imedia_menu_icon_position'] = sanitize_text_field( $megameta['icon_position'] ?? 'left' );

		return $meta;
	}

	public static function parseIconClass( string $classString ): array {
		$classString = trim( $classString );
		if ( $classString === '' ) {
			return array(
				'provider' => '',
				'icon'     => '',
			);
		}

		if ( str_starts_with( $classString, 'dashicons-' ) ) {
			return array(
				'provider' => 'dashicons',
				'icon'     => $classString,
			);
		}

		if ( preg_match( '/^fab?\s+/', $classString ) ) {
			return array(
				'provider' => 'fontawesome',
				'icon'     => $classString,
			);
		}

		if ( preg_match( '/^fa[rsldb]?\s+/', $classString ) ) {
			return array(
				'provider' => 'fontawesome5',
				'icon'     => $classString,
			);
		}

		return array(
			'provider' => '',
			'icon'     => $classString,
		);
	}

	public static function mapThemeToDesign( array $themeSettings ): array {
		$design = array();

		$map = array(
			'arrow_color'                => 'menu_text_color',
			'arrow_color_hover'          => 'menu_text_hover',
			'menu_background'            => 'menu_bar_bg',
			'menu_item_background_hover' => 'menu_bar_bg',
			'panel_background_color'     => 'dropdown_bg',
			'panel_border'               => 'dropdown_bg',
			'panel_header_border_color'  => 'dropdown_bg',
			'panel_header_color'         => 'menu_text_color',
			'flyout_menu_background'     => 'dropdown_bg',
			'flyout_link_color'          => 'menu_text_color',
			'flyout_link_size'           => '',
			'flyout_link_color_hover'    => 'menu_text_hover',
		);

		foreach ( $map as $themeKey => $designKey ) {
			if ( isset( $themeSettings[ $themeKey ] ) && $designKey !== '' ) {
				$design[ $designKey ] = sanitize_text_field( $themeSettings[ $themeKey ] );
			}
		}

		return $design;
	}
}
