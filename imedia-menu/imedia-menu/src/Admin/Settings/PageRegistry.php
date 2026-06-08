<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class PageRegistry {

	private const PAGES = array(
		'imedia-menu'          => array(
			'tabs'  => array( 'general', 'animations' ),
			'title' => 'General',
		),
		'imedia-menu-design'   => array(
			'tabs'  => array( 'design', 'fonts' ),
			'title' => 'Design & Fonts',
		),
		'imedia-menu-mobile'   => array(
			'tabs'  => array( 'mobile', 'visibility' ),
			'title' => 'Mobile & Visibility',
		),
		'imedia-menu-icons'    => array(
			'tabs'  => array( 'icons', 'performance' ),
			'title' => 'Icons',
		),
		'imedia-menu-advanced' => array(
			'tabs'  => array( 'advanced' ),
			'title' => 'Advanced',
		),
	);

	public static function getTabIds( string $slug ): array {
		return self::PAGES[ $slug ]['tabs'] ?? array();
	}

	public static function getTitle( string $slug ): string {
		return self::PAGES[ $slug ]['title'] ?? '';
	}

	public static function getMenuLabel( string $slug ): string {
		$title = self::getTitle( $slug );

		if ( $slug === 'imedia-menu' ) {
			return __( 'iMedia Menu', 'imedia-menu' );
		}

		return $title;
	}

	public static function getPageTitle( string $slug ): string {
		$title = self::getTitle( $slug );

		if ( $slug === 'imedia-menu' ) {
			return __( 'iMedia Menu — General', 'imedia-menu' );
		}

		return sprintf(
			/* translators: %s: settings page title (e.g. Design & Fonts) */
			__( 'iMedia Menu — %s', 'imedia-menu' ),
			$title
		);
	}

	public static function getAllSlugs(): array {
		return array_keys( self::PAGES );
	}
}
