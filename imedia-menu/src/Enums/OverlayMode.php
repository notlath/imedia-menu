<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum OverlayMode: string {

	case Off     = 'off';
	case Desktop = 'desktop';
	case Mobile  = 'mobile';
	case Both    = 'both';

	public function label(): string {
		return match ( $this ) {
			self::Off     => __( 'Off', 'imedia-menu' ),
			self::Desktop => __( 'Desktop only', 'imedia-menu' ),
			self::Mobile  => __( 'Mobile only', 'imedia-menu' ),
			self::Both    => __( 'Desktop and mobile', 'imedia-menu' ),
		};
	}

	public function appliesTo( string $context ): bool {
		return match ( $this ) {
			self::Off     => false,
			self::Desktop => $context === 'desktop',
			self::Mobile  => $context === 'mobile',
			self::Both    => true,
		};
	}

	public static function fromStringOrDefault( ?string $value ): self {
		if ( $value === null || $value === '' ) {
			return self::Off;
		}

		$mode = self::tryFrom( $value );

		return $mode ?? self::Off;
	}
}
