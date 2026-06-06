<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum BadgePosition: string {

	case TopRight = 'top-right';
	case TopLeft  = 'top-left';
	case Inline   = 'inline';

	public function label(): string {
		return match ( $this ) {
			self::TopRight => __( 'Top Right', 'imedia-menu' ),
			self::TopLeft  => __( 'Top Left', 'imedia-menu' ),
			self::Inline   => __( 'Inline', 'imedia-menu' ),
		};
	}
}
