<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum PanelWidth: string {

	case FullWidth      = 'full';
	case ContainerWidth = 'container';
	case Custom         = 'custom';

	public function label(): string {
		return match ( $this ) {
			self::FullWidth      => __( 'Full Viewport Width', 'imedia-menu' ),
			self::ContainerWidth => __( 'Menu Container Width', 'imedia-menu' ),
			self::Custom         => __( 'Custom Width', 'imedia-menu' ),
		};
	}
}
