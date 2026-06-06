<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum TriggerType: string {

	case Hover      = 'hover';
	case Click      = 'click';
	case HoverClick = 'hover_click';

	public function label(): string {
		return match ( $this ) {
			self::Hover      => __( 'Hover', 'imedia-menu' ),
			self::Click      => __( 'Click', 'imedia-menu' ),
			self::HoverClick => __( 'Hover + Click', 'imedia-menu' ),
		};
	}
}
