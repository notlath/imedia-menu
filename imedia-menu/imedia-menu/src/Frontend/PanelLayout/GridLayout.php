<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Visibility\ConditionEvaluator;

/**
 * 12-track grid layout. Each row is a CSS grid; each column declares its own
 * grid-column span (1-12). Rows can be hidden on mobile or desktop via meta.
 *
 * Configuration shape:
 *   rows[]:
 *     columns[]:
 *       span: 1-12 (required when layout is grid; default = floor(12 / count))
 *       blocks[]
 *     meta:
 *       hide_on_mobile: bool
 *       hide_on_desktop: bool
 *       css_class: string
 */
final class GridLayout implements PanelLayoutStrategy {

	private const TRACK_COUNT        = 12;
	private const MIN_SPAN           = 1;
	private const MAX_SPAN           = 12;
	private const DEFAULT_ROW_TRACKS = 12;

	private Registry $registry;
	private ConditionEvaluator $evaluator;

	public function __construct( Registry $registry, ConditionEvaluator $evaluator ) {
		$this->registry  = $registry;
		$this->evaluator = $evaluator;
	}

	public function render( object $panel, int $menuItemId ): string {
		$rows = $panel->config['rows'] ?? array();
		$html = '';

		foreach ( $rows as $row ) {
			$html .= $this->renderRow( $row, $panel, $menuItemId );
		}

		return $html;
	}

	public function requiredStylesheet(): ?string {
		// Enqueued by Assets only when at least one panel uses GridLayout.
		return 'imm-grid.css';
	}

	/**
	 * Resolve a column's grid span, clamped to [1, 12]. Defaults to
	 * a uniform split when the column has no span set.
	 */
	public static function resolveSpan( array $column, int $columnCount ): int {
		if ( isset( $column['span'] ) ) {
			$span = (int) $column['span'];
			return max( self::MIN_SPAN, min( self::MAX_SPAN, $span ) );
		}

		if ( $columnCount <= 0 ) {
			return self::MAX_SPAN;
		}

		return (int) max( self::MIN_SPAN, floor( self::TRACK_COUNT / $columnCount ) );
	}

	private function renderRow( array $row, object $panel, int $menuItemId ): string {
		$columns   = $row['columns'] ?? array();
		$meta      = $row['meta'] ?? array();
		$rowTracks = isset( $row['tracks'] ) ? max( self::MIN_SPAN, min( self::MAX_SPAN, (int) $row['tracks'] ) ) : self::DEFAULT_ROW_TRACKS;

		$classes = array( 'imm-row', 'imm-row--grid' );

		if ( ! empty( $meta['hide_on_mobile'] ) ) {
			$classes[] = 'imm-row--hide-mobile';
		}

		if ( ! empty( $meta['hide_on_desktop'] ) ) {
			$classes[] = 'imm-row--hide-desktop';
		}

		if ( ! empty( $meta['css_class'] ) ) {
			$classes[] = sanitize_html_class( $meta['css_class'] );
		}

		$style = sprintf( '--imm-row-tracks:%d', $rowTracks );

		$html = sprintf(
			'<div class="%s" style="%s">',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $style )
		);

		$columnCount = is_array( $columns ) ? count( $columns ) : 0;

		foreach ( $columns as $column ) {
			$html .= $this->renderColumn( $column, $panel, $menuItemId, $columnCount, $rowTracks );
		}

		$html .= '</div>';

		return $html;
	}

	private function renderColumn( array $column, object $panel, int $menuItemId, int $columnCount, int $rowTracks ): string {
		$blocks = $column['blocks'] ?? array();
		$span   = self::resolveSpan( $column, $columnCount );

		$style = sprintf( 'grid-column:span %d', $span );

		$html = sprintf(
			'<div class="imm-col imm-col--grid" style="%s">',
			esc_attr( $style )
		);

		foreach ( $blocks as $block ) {
			if ( ! $this->evaluator->isBlockVisible( $block ) ) {
				continue;
			}

			$html .= $this->registry->render( $block, $menuItemId );
		}

		$html .= '</div>';

		return $html;
	}
}
