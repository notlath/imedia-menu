<?php

declare(strict_types=1);

namespace IMedia\Menu\Cache;

final class CacheKeyBuilder {

	public function build( int $menuId ): string {
		$components = array(
			'menu_id'   => $menuId,
			'logged_in' => is_user_logged_in(),
			'role'      => $this->getUserRole(),
			'device'    => $this->getDeviceType(),
			'page_id'   => $this->getPageId(),
			'page_type' => $this->getPageType(),
			'locale'    => apply_filters( 'imm_cache_key_locale', get_locale() ),
			'version'   => IMEDIA_MENU_VERSION,
		);

		$hash = md5( serialize( $components ) );

		return "imedia_menu_{$menuId}_{$hash}";
	}

	public function buildPanelKey( int $menuItemId, int $menuId ): string {
		$components = array(
			'menu_item_id' => $menuItemId,
			'menu_id'      => $menuId,
			'logged_in'    => is_user_logged_in(),
			'role'         => $this->getUserRole(),
			'device'       => $this->getDeviceType(),
			'page_id'      => $this->getPageId(),
			'page_type'    => $this->getPageType(),
			'locale'       => apply_filters( 'imm_cache_key_locale', get_locale() ),
			'version'      => IMEDIA_MENU_VERSION,
		);

		$hash = md5( serialize( $components ) );

		return "imedia_menu_panel_{$menuItemId}_{$hash}";
	}

	private function getUserRole(): string {
		if ( ! is_user_logged_in() ) {
			return 'guest';
		}

		$user  = wp_get_current_user();
		$roles = $user->roles;

		return ! empty( $roles ) ? $roles[0] : 'guest';
	}

	private function getDeviceType(): string {
		if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) {
			return 'mobile';
		}

		return 'desktop';
	}

	private function getPageId(): int {
		if ( is_singular() ) {
			$id = get_queried_object_id();
			return is_int( $id ) ? $id : 0;
		}

		return 0;
	}

	private function getPageType(): string {
		if ( is_front_page() ) {
			return 'front';
		}

		if ( is_home() ) {
			return 'home';
		}

		if ( is_singular() ) {
			return 'singular';
		}

		if ( is_archive() ) {
			return 'archive';
		}

		if ( is_search() ) {
			return 'search';
		}

		if ( is_404() ) {
			return '404';
		}

		return 'other';
	}
}
