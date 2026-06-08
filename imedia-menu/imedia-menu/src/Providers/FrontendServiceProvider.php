<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Admin\Settings\LocationOverrides;
use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Enums\MenuOrientation;
use IMedia\Menu\Enums\OverlayMode;
use IMedia\Menu\Frontend\Assets;
use IMedia\Menu\Frontend\MenuWalker;
use IMedia\Menu\Frontend\Overlay;
use IMedia\Menu\Frontend\Sticky;
use IMedia\Menu\Cache\MenuCache;


final class FrontendServiceProvider implements ServiceProvider {

	private const FILTER_PRIORITY_CACHE      = 10;
	private const FILTER_PRIORITY_ATTRIBUTES = 20;

	private Assets $assets;

	private Sticky $sticky;

	private Overlay $overlay;

	public function register(): void {
		$this->assets  = new Assets();
		$this->sticky  = new Sticky();
		$this->overlay = new Overlay();
	}

	public function boot(): void {
		add_action( 'wp_enqueue_scripts', array( $this->assets, 'enqueue' ), 100 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueuePerLocationInlineCss' ), 110 );
		add_filter( 'wp_nav_menu_args', array( $this, 'filterMenuArgs' ) );
		$this->overlay->register();
	}

	/**
	 * Build per-location inline CSS and attach to the imm-base stylesheet.
	 *
	 * Runs on `wp_enqueue_scripts` (priority 110) so the inline `<style>` is
	 * printed in `<head>` alongside imm-base. Doing this from `wp_nav_menu_args`
	 * is too late (the args filter fires after `<head>` is rendered).
	 *
	 * Iterates every registered nav menu location, applies the global settings
	 * merged with per-location overrides, and emits one CSS block per location
	 * scoped to `.imm-nav--location-{slug}`.
	 *
	 * @since 1.0.1  Hoisted from wp_nav_menu_args to wp_enqueue_scripts.
	 * @return void
	 */
	public function enqueuePerLocationInlineCss(): void {
		$globalSettings = get_option( 'imedia_menu_settings', array() );
		$enabled        = $globalSettings['enabled'] ?? true;

		if ( ! $enabled ) {
			return;
		}

		$registered = get_registered_nav_menus();

		if ( empty( $registered ) ) {
			return;
		}

		$locations  = get_nav_menu_locations();
		$aggregated = '';

		foreach ( array_keys( $registered ) as $slug ) {
			if ( empty( $locations[ $slug ] ) ) {
				continue;
			}

			$merged = LocationOverrides::mergeWithGlobal( $globalSettings, $slug );
			$inline = Assets::buildInlineCss( $merged );

			if ( $inline === '' ) {
				continue;
			}

			$scoped      = '.imm-nav--location-' . sanitize_title( $slug );
			$aggregated .= str_replace( '.imm-nav', $scoped, $inline ) . "\n";
		}

		if ( $aggregated !== '' ) {
			wp_add_inline_style( 'imm-base', $aggregated );
		}
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

		$walker = new MenuWalker( $menuId, $mergedSettings, $location );

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

		$menuClass = $this->getMenuClass( $mergedSettings );

		$args['walker']               = $walker;
		$args['container']            = 'nav';
		$args['container_class']      = $containerClass;
		$args['container_aria_label'] = $this->getMenuLabel( $menuId );
		$args['menu_class']           = $menuClass;
		$args['items_wrap']           = $this->getItemsWrap( $menuId );
		$args['fallback_cb']          = false;
		$args['echo']                 = false;

		add_filter(
			'wp_nav_menu',
			function ( string $navHtml, object $navArgs ) use ( $mergedSettings, $walker, $cache, $menuId, $fromCache, $cacheEnabled ): string {
				if ( ! isset( $navArgs->walker ) || ! $navArgs->walker instanceof MenuWalker ) {
					return $navHtml;
				}

				$trigger   = $walker->getTriggerType();
				$delay     = (int) ( $mergedSettings['hover_delay'] ?? 200 );
				$animation = $mergedSettings['default_animation'] ?? 'fade';
				$dataAttrs = sprintf(
					' data-trigger="%s" data-hover-delay="%d" data-animation="%s" data-orientation="%s"',
					esc_attr( $trigger ),
					$delay,
					esc_attr( $animation ),
					esc_attr( $walker->getOrientation()->value )
				);

				$stickyAttrs = Sticky::attributes( $mergedSettings );
				foreach ( $stickyAttrs as $key => $value ) {
					$dataAttrs .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
				}

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

		$orientation = MenuOrientation::fromStringOrDefault( $settings['orientation'] ?? 'horizontal' );
		$classes[]   = 'imm-nav--' . $orientation->value;

		$overlay = OverlayMode::fromStringOrDefault( $settings['overlay'] ?? 'off' );
		if ( $overlay !== OverlayMode::Off ) {
			$classes[] = 'imm-nav--overlay-' . $overlay->value;
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

	private function getMenuClass( array $settings ): string {
		$orientation = MenuOrientation::fromStringOrDefault( $settings['orientation'] ?? 'horizontal' );

		return 'imm-menu imm-menu--' . $orientation->value;
	}
}
