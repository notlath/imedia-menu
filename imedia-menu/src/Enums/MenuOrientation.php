<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum MenuOrientation: string {

	case Horizontal = 'horizontal';
	case Vertical   = 'vertical';
	case Accordion  = 'accordion';

	public function label(): string {
		return match ( $this ) {
			self::Horizontal => __( 'Horizontal', 'imedia-menu' ),
			self::Vertical   => __( 'Vertical', 'imedia-menu' ),
			self::Accordion  => __( 'Accordion', 'imedia-menu' ),
		};
	}

	public function description(): string {
		return match ( $this ) {
			self::Horizontal => __( 'Top-level menu items render in a horizontal bar.', 'imedia-menu' ),
			self::Vertical   => __( 'Top-level menu items stack vertically (sidebar-style). Submenus open on hover/click.', 'imedia-menu' ),
			self::Accordion  => __( 'Vertical orientation with always-expanded submenus. Click-only trigger; no hover.', 'imedia-menu' ),
		};
	}

	/**
	 * Returns the trigger type this orientation forces, or null if any trigger is allowed.
	 *
	 * Accordion forces click trigger (no hover — submenus are always visible).
	 * Horizontal and vertical allow any trigger — the existing per-location
	 * `trigger_type` setting continues to apply.
	 */
	public function requiredTriggerType(): ?string {
		return match ( $this ) {
			self::Accordion  => 'click',
			self::Horizontal => null,
			self::Vertical   => null,
		};
	}

	public static function fromStringOrDefault( ?string $value ): self {
		if ( $value === null || $value === '' ) {
			return self::Horizontal;
		}

		$orientation = self::tryFrom( $value );

		return $orientation ?? self::Horizontal;
	}
}
