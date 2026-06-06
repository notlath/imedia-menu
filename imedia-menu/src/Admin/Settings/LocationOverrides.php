<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class LocationOverrides {

	public const OPTION_KEY = 'imedia_menu_location_overrides';

	public static function getAll(): array {
		$overrides = get_option( self::OPTION_KEY, array() );

		return is_array( $overrides ) ? $overrides : array();
	}

	public static function getForLocation( string $slug ): array {
		$all = self::getAll();

		return $all[ $slug ] ?? array();
	}

	public static function setForLocation( string $slug, array $overrides ): void {
		$all          = self::getAll();
		$all[ $slug ] = $overrides;
		update_option( self::OPTION_KEY, $all );
	}

	public static function clearForLocation( string $slug ): void {
		$all = self::getAll();
		unset( $all[ $slug ] );
		update_option( self::OPTION_KEY, $all );
	}

	public static function mergeWithGlobal( array $globalSettings, string $slug ): array {
		$overrides = self::getForLocation( $slug );

		if ( empty( $overrides ) ) {
			return $globalSettings;
		}

		return array_merge( $globalSettings, $overrides );
	}
}
