<?php

declare(strict_types=1);

namespace IMedia\Menu\Blocks\Navigation;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Frontend\Assets as FrontendAssets;
use IMedia\Menu\Frontend\MenuWalker;
use IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository;

final class Navigation {

	public static function register(): void {
		register_block_type( IMEDIA_MENU_DIR . '/assets/blocks/navigation-block' );
	}

	public static function render( array $attributes, string $content = '' ): string {
		$menuId    = $attributes['menuId'] ?? 0;
		$className = $attributes['className'] ?? '';

		if ( $menuId === 0 ) {
			return sprintf(
				'<nav class="%s"><p>%s</p></nav>',
				esc_attr( $className ),
				esc_html__( 'Select a menu in the block settings.', 'imedia-menu' )
			);
		}

		$menu = wp_get_nav_menu_object( $menuId );

		if ( ! $menu ) {
			return sprintf(
				'<nav class="%s"><p>%s</p></nav>',
				esc_attr( $className ),
				esc_html__( 'Menu not found.', 'imedia-menu' )
			);
		}

		self::ensureAssetsLoaded();

		$settings       = get_option( 'imedia_menu_settings', array() );
		$location       = self::detectLocation( $menuId );
		$mergedSettings = $location
			? LocationOverrides::mergeWithGlobal( $settings, $location )
			: $settings;

		$walker = new MenuWalker( $menuId, $mergedSettings, $location ?? '' );

		add_filter( 'wp_nav_menu_items', array( self::class, 'maybePrependMobileToggle' ), 10, 2 );

		$menuHtml = wp_nav_menu(
			array(
				'menu'                 => $menuId,
				'menu_class'           => 'imm-menu',
				'container'            => 'nav',
				'container_class'      => 'imm-nav ' . $className,
				'container_aria_label' => $menu->name,
				'fallback_cb'          => false,
				'walker'               => $walker,
				'echo'                 => false,
			)
		);

		remove_filter( 'wp_nav_menu_items', array( self::class, 'maybePrependMobileToggle' ), 10 );

		$menuHtml = self::wrapMenu( $menuHtml, $menuId, $mergedSettings );

		return $menuHtml;
	}

	private static function ensureAssetsLoaded(): void {
		if ( ! wp_style_is( 'imm-base', 'enqueued' ) ) {
			( new FrontendAssets() )->enqueue();
		}
	}

	public static function maybePrependMobileToggle( string $items, object $args ): string {
		if ( str_contains( $args->container_class ?? '', 'imm-nav' ) ) {
			$location = self::detectLocation( $args->menu->term_id ?? 0 );
			if ( $location && self::locationHasToggleBar( $location ) ) {
				return $items;
			}

			$toggle = sprintf(
				'<button class="imm-mobile-toggle" aria-expanded="false" aria-controls="imm-menu-%d" aria-label="%s">
					<span class="imm-hamburger"><span></span><span></span><span></span></span>
				</button>',
				$args->menu->term_id ?? 0,
				esc_attr__( 'Toggle navigation menu', 'imedia-menu' )
			);
			$items  = $toggle . $items;
		}

		return $items;
	}

	private static function detectLocation( int $menuId ): ?string {
		$locations = get_nav_menu_locations();
		if ( ! is_array( $locations ) ) {
			return null;
		}
		foreach ( $locations as $slug => $assignedMenuId ) {
			if ( (int) $assignedMenuId === $menuId ) {
				return (string) $slug;
			}
		}
		return null;
	}

	private static function locationHasToggleBar( string $location ): bool {
		return ( new ToggleBarRepository() )->hasBlocks( $location );
	}

	private static function wrapMenu( string $menuHtml, int $menuId, array $settings ): string {
		$trigger = $settings['trigger_type'] ?? 'hover';
		$delay   = (int) ( $settings['hover_delay'] ?? 200 );

		$menuHtml = str_replace(
			'<nav',
			sprintf( '<nav data-trigger="%s" data-hover-delay="%d"', esc_attr( $trigger ), $delay ),
			$menuHtml
		);

		$menuHtml = str_replace(
			'class="imm-menu">',
			'class="imm-menu" role="menubar">',
			$menuHtml
		);

		$inlineCss = FrontendAssets::buildInlineCss( $settings );
		if ( $inlineCss !== '' ) {
			$menuHtml .= '<style>' . $inlineCss . '</style>';
		}

		return $menuHtml;
	}
}
