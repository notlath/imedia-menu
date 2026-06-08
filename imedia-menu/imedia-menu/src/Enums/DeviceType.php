<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum DeviceType: string {

	case Desktop = 'desktop';
	case Tablet  = 'tablet';
	case Mobile  = 'mobile';

	public function label(): string {
		return match ( $this ) {
			self::Desktop => __( 'Desktop', 'imedia-menu' ),
			self::Tablet  => __( 'Tablet', 'imedia-menu' ),
			self::Mobile  => __( 'Mobile', 'imedia-menu' ),
		};
	}

	public function breakpoint(): int {
		return match ( $this ) {
			self::Desktop => 1024,
			self::Tablet  => 768,
			self::Mobile  => 0,
		};
	}
}
