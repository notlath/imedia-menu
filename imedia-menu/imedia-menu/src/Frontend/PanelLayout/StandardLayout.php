<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Visibility\ConditionEvaluator;

/**
 * Standard N-column layout. Columns are flex:1 with optional per-column width.
 *
 * This is the original imedia-menu behavior, extracted unchanged from
 * MegaPanelRenderer so existing panels render pixel-identical to M0.
 */
final class StandardLayout implements PanelLayoutStrategy {

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
		// Standard layout is built into imm-base.css.
		return null;
	}

	private function renderRow( array $row, object $panel, int $menuItemId ): string {
		$columns = $row['columns'] ?? array();
		$html    = '<div class="imm-row">';

		foreach ( $columns as $column ) {
			$html .= $this->renderColumn( $column, $panel, $menuItemId );
		}

		$html .= '</div>';

		return $html;
	}

	private function renderColumn( array $column, object $panel, int $menuItemId ): string {
		$blocks       = $column['blocks'] ?? array();
		$width        = $column['width'] ?? 'auto';
		$columnStyles = $column['styles'] ?? array();

		$style = '--imm-col-width:' . $width;

		if ( ! empty( $columnStyles ) ) {
			if ( isset( $columnStyles['padding'] ) ) {
				$p      = $columnStyles['padding'];
				$style .= ';padding:' . $p['top'] . ' ' . $p['right'] . ' ' . $p['bottom'] . ' ' . $p['left'];
			}
		}

		$html = sprintf(
			'<div class="imm-col" style="%s">',
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
