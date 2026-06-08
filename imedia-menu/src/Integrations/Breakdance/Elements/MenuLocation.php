<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Breakdance\Elements;

use Breakdance\Elements\Element;

final class MenuLocation extends Element {

	public static function name(): string {
		return 'iMedia Menu Location';
	}

	public static function slug(): string {
		return 'imedia-menu-location';
	}

	public static function category(): string {
		return 'site';
	}

	public static function tag(): string {
		return 'div';
	}

	public static function properties(): array {
		return array(
			'controls' => array(
				array(
					'id'      => 'location',
					'label'   => 'Menu Location',
					'type'    => 'dropdown',
					'items'   => self::getLocationOptions(),
					'default' => '',
				),
			),
			'defaults' => array(
				'location' => '',
			),
		);
	}

	public static function template(): string {
		return '%%SSR%%';
	}

	public static function ssr( array $attributes ): string {
		$location = $attributes['location'] ?? '';

		if ( empty( $location ) ) {
			return '<p>' . esc_html__( 'Select a menu location in the element settings.', 'imedia-menu' ) . '</p>';
		}

		return wp_nav_menu(
			array(
				'theme_location' => $location,
				'echo'           => false,
				'fallback_cb'    => '__return_false',
			)
		);
	}

	private static function getLocationOptions(): array {
		$locations = get_registered_nav_menus();
		$options   = array(
			array(
				'label' => esc_html__( '— Select —', 'imedia-menu' ),
				'value' => '',
			),
		);
		foreach ( $locations as $slug => $name ) {
			$options[] = array(
				'label' => esc_html( $name ),
				'value' => esc_attr( $slug ),
			);
		}
		return $options;
	}
}
