<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\PanelLayout;

/**
 * Flyout layout. The panel itself renders no inner content; the MenuWalker
 * emits the nested <ul><li> naturally for the menu item's children.
 *
 * Returning an empty string is correct: the panel record is stored but the
 * walker's child traversal handles the visible menu structure.
 */
final class FlyoutLayout implements PanelLayoutStrategy {

	public function render( object $panel, int $menuItemId ): string {
		return '';
	}

	public function requiredStylesheet(): ?string {
		return null;
	}
}
