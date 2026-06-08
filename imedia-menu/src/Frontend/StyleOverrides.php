<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

final class StyleOverrides {

	public const PROPERTIES = array(
		'menu_item_background_from'            => '--imm-item-bg-from',
		'menu_item_background_to'              => '--imm-item-bg-to',
		'menu_item_background_hover_from'      => '--imm-item-bg-hover-from',
		'menu_item_background_hover_to'        => '--imm-item-bg-hover-to',
		'menu_item_link_color'                 => '--imm-item-color',
		'menu_item_link_color_hover'           => '--imm-item-color-hover',
		'menu_item_link_weight'                => '--imm-item-weight',
		'menu_item_link_weight_hover'          => '--imm-item-weight-hover',
		'menu_item_font_size'                  => '--imm-item-font-size',
		'menu_item_link_text_align'            => '--imm-item-text-align',
		'menu_item_link_text_transform'        => '--imm-item-text-transform',
		'menu_item_link_text_decoration'       => '--imm-item-text-decoration',
		'menu_item_link_text_decoration_hover' => '--imm-item-text-decoration-hover',
		'menu_item_border_color'               => '--imm-item-border-color',
		'menu_item_border_color_hover'         => '--imm-item-border-color-hover',
		'menu_item_border_top'                 => '--imm-item-border-top',
		'menu_item_border_right'               => '--imm-item-border-right',
		'menu_item_border_bottom'              => '--imm-item-border-bottom',
		'menu_item_border_left'                => '--imm-item-border-left',
		'menu_item_border_radius_top_left'     => '--imm-item-radius-tl',
		'menu_item_border_radius_top_right'    => '--imm-item-radius-tr',
		'menu_item_border_radius_bottom_right' => '--imm-item-radius-br',
		'menu_item_border_radius_bottom_left'  => '--imm-item-radius-bl',
		'menu_item_icon_size'                  => '--imm-item-icon-size',
		'menu_item_icon_color'                 => '--imm-item-icon-color',
		'menu_item_icon_color_hover'           => '--imm-item-icon-color-hover',
		'menu_item_padding_left'               => '--imm-item-padding-left',
		'menu_item_padding_right'              => '--imm-item-padding-right',
		'menu_item_padding_top'                => '--imm-item-padding-top',
		'menu_item_padding_bottom'             => '--imm-item-padding-bottom',
		'menu_item_margin_left'                => '--imm-item-margin-left',
		'menu_item_margin_right'               => '--imm-item-margin-right',
		'menu_item_margin_top'                 => '--imm-item-margin-top',
		'menu_item_margin_bottom'              => '--imm-item-margin-bottom',
		'menu_item_height'                     => '--imm-item-height',
		'panel_width'                          => '--imm-panel-width',
		'panel_horizontal_offset'              => '--imm-panel-horizontal-offset',
		'panel_vertical_offset'                => '--imm-panel-vertical-offset',
		'panel_background_from'                => '--imm-panel-bg-from',
		'panel_background_to'                  => '--imm-panel-bg-to',
	);

	public const COLOR_PROPERTIES = array(
		'menu_item_background_from',
		'menu_item_background_to',
		'menu_item_background_hover_from',
		'menu_item_background_hover_to',
		'menu_item_link_color',
		'menu_item_link_color_hover',
		'menu_item_border_color',
		'menu_item_border_color_hover',
		'menu_item_icon_color',
		'menu_item_icon_color_hover',
		'panel_background_from',
		'panel_background_to',
	);

	public static function cssVarFor( string $property ): ?string {
		return self::PROPERTIES[ $property ] ?? null;
	}

	public static function sanitizeValue( string $property, string $value ): string {
		$value = trim( $value );

		if ( $value === '' ) {
			return '';
		}

		if ( in_array( $property, self::COLOR_PROPERTIES, true ) ) {
			if ( preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([^)]+\)|hsla?\([^)]+\))$/', $value ) ) {
				return $value;
			}
			return '';
		}

		if ( in_array( $property, array( 'menu_item_font_size', 'menu_item_icon_size', 'menu_item_height', 'menu_item_border_top', 'menu_item_border_right', 'menu_item_border_bottom', 'menu_item_border_left', 'menu_item_border_radius_top_left', 'menu_item_border_radius_top_right', 'menu_item_border_radius_bottom_right', 'menu_item_border_radius_bottom_left', 'menu_item_padding_left', 'menu_item_padding_right', 'menu_item_padding_top', 'menu_item_padding_bottom', 'menu_item_margin_left', 'menu_item_margin_right', 'menu_item_margin_top', 'menu_item_margin_bottom', 'panel_width', 'panel_horizontal_offset', 'panel_vertical_offset' ), true ) ) {
			if ( preg_match( '/^-?[\d.]+(px|em|rem|%|vh|vw|pt|ch)?$/', $value ) ) {
				return $value;
			}
			return '';
		}

		if ( 'menu_item_link_weight' === $property || 'menu_item_link_weight_hover' === $property ) {
			$allowed = array( 'inherit', 'normal', 'bold', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
			return in_array( $value, $allowed, true ) ? $value : '';
		}

		if ( 'menu_item_link_text_align' === $property ) {
			$allowed = array( 'left', 'center', 'right', 'inherit' );
			return in_array( $value, $allowed, true ) ? $value : '';
		}

		if ( 'menu_item_link_text_transform' === $property ) {
			$allowed = array( 'none', 'uppercase', 'lowercase', 'capitalize', 'inherit' );
			return in_array( $value, $allowed, true ) ? $value : '';
		}

		if ( 'menu_item_link_text_decoration' === $property || 'menu_item_link_text_decoration_hover' === $property ) {
			$allowed = array( 'none', 'underline', 'overline', 'line-through', 'inherit' );
			return in_array( $value, $allowed, true ) ? $value : '';
		}

		return sanitize_text_field( $value );
	}

	public static function getItemStyles( int $itemId ): string {
		$enabled = get_post_meta( $itemId, '_imedia_menu_styles_enabled', true );
		if ( ! is_array( $enabled ) || empty( $enabled ) ) {
			return '';
		}

		$values = get_post_meta( $itemId, '_imedia_menu_styles_values', true );
		if ( ! is_array( $values ) ) {
			$values = array();
		}

		$css = '';

		foreach ( $enabled as $property ) {
			$cssVar = self::cssVarFor( $property );
			if ( $cssVar === null || empty( $values[ $property ] ) ) {
				continue;
			}

			$sanitized = self::sanitizeValue( $property, (string) $values[ $property ] );
			if ( $sanitized === '' ) {
				continue;
			}

			$css .= "{$cssVar}:{$sanitized};";
		}

		return $css;
	}
}
