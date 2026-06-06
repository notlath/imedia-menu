<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Visibility\ConditionEvaluator;

final class MegaPanelRenderer {

	private Registry $registry;
	private ConditionEvaluator $evaluator;

	public function __construct() {
		$this->registry  = new Registry();
		$this->evaluator = new ConditionEvaluator();
	}

	public function render( object $panel ): string {
		$config      = $panel->config ?? array();
		$rows        = $config['rows'] ?? array();
		$html        = '';
		$menuItemId  = (int) ( $panel->menu_item_id ?? 0 );

		foreach ( $rows as $row ) {
			$html .= $this->renderRow( $row, $panel, $menuItemId );
		}

		return $html;
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
