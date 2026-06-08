<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Enums\PanelLayoutType;
use IMedia\Menu\Frontend\PanelLayout\PanelLayoutStrategyRegistry;
use IMedia\Menu\Visibility\ConditionEvaluator;

final class MegaPanelRenderer {

	private Registry $registry;
	private ConditionEvaluator $evaluator;
	private PanelLayoutStrategyRegistry $strategies;

	public function __construct() {
		$this->registry   = new Registry();
		$this->evaluator  = new ConditionEvaluator();
		$this->strategies = new PanelLayoutStrategyRegistry( $this->registry, $this->evaluator );
	}

	public function render( object $panel ): string {
		$layoutType = PanelLayoutType::fromStringOrDefault( $panel->layout_type ?? null );
		$menuItemId = (int) ( $panel->menu_item_id ?? 0 );

		$strategy = $this->strategies->get( $layoutType );

		return $strategy->render( $panel, $menuItemId );
	}

	/**
	 * Returns the list of stylesheets that should be enqueued for the given
	 * layout types. Callers (e.g. Assets) can use this to conditionally load
	 * CSS that would otherwise be wasted on layouts the user isn't using.
	 *
	 * @param string[] $layoutTypeValues
	 * @return string[]
	 */
	public function requiredStylesheetsFor( array $layoutTypeValues ): array {
		$layouts = array();
		foreach ( $layoutTypeValues as $value ) {
			$layouts[] = PanelLayoutType::fromStringOrDefault( $value );
		}

		return $this->strategies->requiredStylesheets( $layouts );
	}
}
