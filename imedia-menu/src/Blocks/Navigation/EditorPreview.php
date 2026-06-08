<?php

declare(strict_types=1);

namespace IMedia\Menu\Blocks\Navigation;

final class EditorPreview {

	public static function getMenuOptions(): array {
		$menus     = wp_get_nav_menus();
		$options   = array(
			array(
				'value' => 0,
				'label' => __( 'Select a menu\u2026', 'imedia-menu' ),
			),
		);
		$locations = get_nav_menu_locations();

		foreach ( $menus as $menu ) {
			$locationSlug = self::findLocationForMenu( $menu->term_id, $locations );
			$label        = $menu->name;
			if ( $locationSlug !== null ) {
				$label .= ' (' . $locationSlug . ')';
			}
			$options[] = array(
				'value'    => $menu->term_id,
				'label'    => $label,
				'location' => $locationSlug ?? '',
			);
		}

		return $options;
	}

	public static function getPreviewHtml( int $menuId ): string {
		if ( $menuId === 0 ) {
			return sprintf(
				'<p>%s</p>',
				esc_html__( 'Select a menu to preview.', 'imedia-menu' )
			);
		}

		return self::render( $menuId );
	}

	private static function render( int $menuId ): string {
		$menu = wp_get_nav_menu_object( $menuId );
		if ( ! $menu ) {
			return sprintf(
				'<p>%s</p>',
				esc_html__( 'Menu not found.', 'imedia-menu' )
			);
		}

		$settings = get_option( 'imedia_menu_settings', array() );
		$location = self::findLocationForMenu( $menuId, get_nav_menu_locations() );
		$merged   = $location
			? \IMedia\Menu\Admin\Settings\LocationOverrides::mergeWithGlobal( $settings, $location )
			: $settings;

		$walker = new \IMedia\Menu\Frontend\MenuWalker( $menuId, $merged, $location ?? '' );

		return wp_nav_menu(
			array(
				'menu'                 => $menuId,
				'menu_class'           => 'imm-menu',
				'container'            => 'nav',
				'container_class'      => 'imm-nav imm-nav--preview',
				'container_aria_label' => $menu->name,
				'fallback_cb'          => false,
				'walker'               => $walker,
				'echo'                 => false,
			)
		);
	}

	private static function findLocationForMenu( int $menuId, array $locations ): ?string {
		foreach ( $locations as $slug => $assignedMenuId ) {
			if ( (int) $assignedMenuId === $menuId ) {
				return (string) $slug;
			}
		}
		return null;
	}
}
