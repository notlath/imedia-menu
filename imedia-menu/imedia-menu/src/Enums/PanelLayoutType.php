<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum PanelLayoutType: string {

	case Flyout   = 'flyout';
	case Standard = 'columns';
	case Grid     = 'grid';

	public function label(): string {
		return match ( $this ) {
			self::Flyout   => __( 'Flyout Menu', 'imedia-menu' ),
			self::Standard => __( 'Standard Layout (N columns)', 'imedia-menu' ),
			self::Grid     => __( 'Grid Layout (12-track)', 'imedia-menu' ),
		};
	}

	public function description(): string {
		return match ( $this ) {
			self::Flyout   => __( 'Single-column flyout. Blocks are not used; child menu items render as a plain nested list.', 'imedia-menu' ),
			self::Standard => __( 'Multi-column panel with a fixed number of equal-width columns.', 'imedia-menu' ),
			self::Grid     => __( '12-track grid where each column sets its own span (1-12).', 'imedia-menu' ),
		};
	}

	/**
	 * Resolve a string to a layout type, defaulting to Standard for unknown values.
	 *
	 * Backward compatibility: existing panels stored with the legacy value 'columns'
	 * map to Standard. Empty or unknown strings also fall back to Standard.
	 */
	public static function fromStringOrDefault( ?string $value ): self {
		if ( $value === null || $value === '' ) {
			return self::Standard;
		}

		$layout = self::tryFrom( $value );

		return $layout ?? self::Standard;
	}
}
