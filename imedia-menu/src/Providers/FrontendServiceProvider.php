<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Frontend\Assets;
use IMedia\Menu\Frontend\MenuWalker;
use IMedia\Menu\Cache\MenuCache;

final class FrontendServiceProvider implements ServiceProvider {

	private Assets $assets;

	public function register(): void {
		$this->assets = new Assets();
	}

	public function boot(): void {
		add_action( 'wp_enqueue_scripts', array( $this->assets, 'enqueue' ), 100 );
		add_filter( 'wp_nav_menu_args', array( $this, 'filterMenuArgs' ), 10, 2 );
	}

	public function filterMenuArgs( array $args ): array {
		$globalSettings = get_option( 'imedia_menu_settings', array() );
		$enabled        = $globalSettings['enabled'] ?? true;

		if ( ! $enabled ) {
			return $args;
		}

		$location = $args['theme_location'] ?? '';

		if ( empty( $location ) ) {
			return $args;
		}

		$mergedSettings = LocationOverrides::mergeWithGlobal( $globalSettings, $location );

		$menuId = $this->getMenuIdFromLocation( $location );

		if ( $menuId === 0 ) {
			return $args;
		}

		$walker = new MenuWalker( $menuId, $mergedSettings );

		$cache        = new MenuCache();
		$cacheKey     = null;
		$cached       = $cache->getMenuHtml( $menuId, $cacheKey );
		$fromCache    = $cached !== null;
		$cacheEnabled = $mergedSettings['enable_caching'] ?? true;

		if ( $fromCache && $cacheEnabled ) {
			add_filter(
				'wp_nav_menu',
				function ( string $navHtml, object $navArgs ) use ( $cached ): string {
					if ( isset( $navArgs->walker ) && $navArgs->walker instanceof MenuWalker ) {
						return $cached;
					}
					return $navHtml;
				},
				10,
				2
			);
		}

		$containerClass = $this->getContainerClass( $location, $mergedSettings );

		$this->addPerLocationInlineCss( $location, $mergedSettings );

		$args['walker']               = $walker;
		$args['container']            = 'nav';
		$args['container_class']      = $containerClass;
		$args['container_aria_label'] = $this->getMenuLabel( $menuId );
		$args['menu_class']           = 'imm-menu';
		$args['items_wrap']           = $this->getItemsWrap( $menuId );
		$args['fallback_cb']          = false;
		$args['echo']                 = false;

		add_filter(
			'wp_nav_menu',
			function ( string $navHtml, object $navArgs ) use ( $mergedSettings, $walker, $cache, $menuId, $fromCache, $cacheEnabled ): string {
				if ( ! isset( $navArgs->walker ) || ! $navArgs->walker instanceof MenuWalker ) {
					return $navHtml;
				}

				$trigger    = $mergedSettings['trigger_type'] ?? 'hover';
				$delay      = (int) ( $mergedSettings['hover_delay'] ?? 200 );
				$animation  = $mergedSettings['default_animation'] ?? 'fade';
				$dataAttrs  = sprintf(
					' data-trigger="%s" data-hover-delay="%d" data-animation="%s"',
					esc_attr( $trigger ),
					$delay,
					esc_attr( $animation )
				);

				$navHtml = str_replace( '<nav', '<nav' . $dataAttrs, $navHtml );

				if ( $cacheEnabled && ! $fromCache ) {
					$durationSetting = (int) ( $mergedSettings['cache_duration'] ?? 60 );
					$cache->setMenuHtml( $menuId, $navHtml, $durationSetting * 60 );
				}

				return $navHtml;
			},
			20,
			2
		);

		return $args;
	}

	private function addPerLocationInlineCss( string $location, array $settings ): void {
		$locationClass = '.imm-nav--location-' . sanitize_title( $location );
		$cssVars       = array();

		if ( ! empty( $settings['menu_bar_bg'] ) ) {
			$cssVars[] = '--imm-bg:' . $settings['menu_bar_bg'];
		}

		if ( ! empty( $settings['menu_bar_height'] ) ) {
			$cssVars[] = '--imm-height:' . (int) $settings['menu_bar_height'] . 'px';
		}

		if ( ! empty( $settings['menu_text_color'] ) ) {
			$cssVars[] = '--imm-text:' . $settings['menu_text_color'];
		}

		if ( ! empty( $settings['menu_text_hover'] ) ) {
			$cssVars[] = '--imm-text-hover:' . $settings['menu_text_hover'];
		}

		if ( ! empty( $settings['dropdown_bg'] ) ) {
			$cssVars[] = '--imm-dropdown-bg:' . $settings['dropdown_bg'];
		}

		if ( empty( $cssVars ) ) {
			return;
		}

		$css = sprintf(
			'%s { %s }',
			esc_attr( $locationClass ),
			implode( ';', $cssVars )
		);

		wp_add_inline_style( 'imm-base', $css );
	}

	private function getMenuIdFromLocation( string $location ): int {
		$locations = get_nav_menu_locations();

		return (int) ( $locations[ $location ] ?? 0 );
	}

	private function getContainerClass( string $location, array $settings ): string {
		$classes = array( 'imm-nav' );

		if ( ! empty( $settings['transparent_mode'] ) ) {
			$classes[] = 'imm-nav--transparent';
		}

		if ( ! empty( $settings['sticky'] ) ) {
			$classes[] = 'imm-nav--sticky';
		}

		$classes[] = 'imm-nav--location-' . $location;

		return implode( ' ', $classes );
	}

	private function getMenuLabel( int $menuId ): string {
		$menu = wp_get_nav_menu_object( $menuId );

		return $menu ? $menu->name : __( 'Navigation', 'imedia-menu' );
	}

	private function getItemsWrap( int $menuId ): string {
		return sprintf(
			'<ul id="imm-menu-%d" class="%%2$s" role="menu">%%3$s</ul>',
			$menuId
		);
	}
}
