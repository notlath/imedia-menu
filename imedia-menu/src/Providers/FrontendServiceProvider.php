<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Frontend\Assets;
use IMedia\Menu\Frontend\MenuWalker;
use IMedia\Menu\Cache\MenuCache;


final class FrontendServiceProvider implements ServiceProvider {

	private const FILTER_PRIORITY_CACHE      = 10;
	private const FILTER_PRIORITY_ATTRIBUTES = 20;

	private Assets $assets;

	public function register(): void {
		$this->assets = new Assets();
	}

	public function boot(): void {
		add_action( 'wp_enqueue_scripts', array( $this->assets, 'enqueue' ), 100 );
		add_filter( 'wp_nav_menu_args', array( $this, 'filterMenuArgs' ) );
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
				self::FILTER_PRIORITY_CACHE,
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
			self::FILTER_PRIORITY_ATTRIBUTES,
			2
		);

		return $args;
	}

	private function addPerLocationInlineCss( string $location, array $settings ): void {
		// Note: this method runs from the wp_nav_menu_args filter, which fires AFTER
		// 'imm-base' styles are printed in <head>. Per-location CSS added here is
		// therefore too late to appear on the page. The global design CSS added by
		// Assets::enqueue() (via Assets::maybeInlineCustomCss()) is the primary
		// mechanism. This method is kept for future use (e.g., when a per-location
		// style is registered and enqueued at wp_enqueue_scripts time).

		$inlineCss = Assets::buildInlineCss( $settings );

		if ( $inlineCss === '' ) {
			return;
		}

		$locationClass = '.imm-nav--location-' . sanitize_title( $location );
		$wrapped       = str_replace( '.imm-nav', $locationClass, $inlineCss );

		wp_add_inline_style( 'imm-base', $wrapped );
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
