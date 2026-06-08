<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Visibility\ConditionEvaluator;

interface PanelLayoutStrategy {

	/**
	 * Returns the HTML for the panel inner content.
	 *
	 * Implementations MUST escape all output. Block-level rendering
	 * (delegating to the ContentBlock Registry) is the caller's responsibility —
	 * strategies only own the row/column skeleton.
	 */
	public function render( object $panel, int $menuItemId ): string;

	/**
	 * The CSS file (relative to the plugin's assets/frontend/css/) that this
	 * strategy requires to render correctly. Used by Assets to enqueue
	 * conditionally.
	 */
	public function requiredStylesheet(): ?string;
}
