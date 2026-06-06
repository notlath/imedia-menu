<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class MenuLinksBlock implements ContentBlock {

	private int $menuItemId = 0;

	public function setMenuItemId( int $menuItemId ): void {
		$this->menuItemId = $menuItemId;
	}

	public function type(): string {
		return 'menu_links';
	}

	public function title(): string {
		return __( 'Menu Links', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$showDescriptions = $config['show_descriptions'] ?? false;
		$showIcons        = $config['show_icons'] ?? false;
		$source           = $config['source'] ?? 'children';

		if ( $source === 'children' && $this->menuItemId > 0 ) {
			return $this->renderChildren( $showDescriptions, $showIcons );
		}

		return $this->renderPlaceholder( $showDescriptions, $showIcons );
	}

	public function defaultConfig(): array {
		return array(
			'source'            => 'children',
			'show_descriptions' => false,
			'show_icons'        => true,
			'max_depth'         => 0,
		);
	}

	private function renderChildren( bool $showDescriptions, bool $showIcons ): string {
		$terms = wp_get_post_terms( $this->menuItemId, 'nav_menu' );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return $this->renderPlaceholder( $showDescriptions, $showIcons );
		}

		$menuId    = $terms[0]->term_id;
		$menuItems = wp_get_nav_menu_items( $menuId );

		if ( empty( $menuItems ) ) {
			return $this->renderPlaceholder( $showDescriptions, $showIcons );
		}

		$children = array();

		foreach ( $menuItems as $item ) {
			if ( (int) $item->menu_item_parent === $this->menuItemId ) {
				$children[] = $item;
			}
		}

		if ( empty( $children ) ) {
			return $this->renderPlaceholder( $showDescriptions, $showIcons );
		}

		$hasGrandchildren = false;

		foreach ( $menuItems as $item ) {
			foreach ( $children as $child ) {
				if ( (int) $item->menu_item_parent === $child->ID ) {
					$hasGrandchildren = true;
					break 2;
				}
			}
		}

		$html  = '<ul class="imm-block imm-block--links" role="menu">';

		foreach ( $children as $child ) {
			$isDisabled = (bool) get_post_meta( $child->ID, '_imedia_menu_disable_link', true );
			$icon       = get_post_meta( $child->ID, '_imedia_menu_icon', true );
			$iconPos    = get_post_meta( $child->ID, '_imedia_menu_icon_position', true ) ?: 'before';
			$badgeText  = get_post_meta( $child->ID, '_imedia_menu_badge_text', true );
			$desc       = get_post_meta( $child->ID, '_imedia_menu_description', true );
			$hasKids    = false;

			foreach ( $menuItems as $maybeChild ) {
				if ( (int) $maybeChild->menu_item_parent === $child->ID ) {
					$hasKids = true;
					break;
				}
			}

			$classes = 'imm-item';
			$classes .= $hasKids ? ' imm-item--has-children' : '';

			$html .= sprintf(
				'<li class="%s" role="none">',
				esc_attr( $classes )
			);

			$ariaAttrs = 'role="menuitem"';

			if ( $hasKids ) {
				$ariaAttrs .= ' aria-haspopup="true" aria-expanded="false"';
			}

			if ( $isDisabled ) {
				$html .= sprintf(
					'<span class="imm-link imm-link--disabled" %s aria-disabled="true" tabindex="-1">',
					$ariaAttrs
				);
			} else {
				$html .= sprintf(
					'<a href="%s" class="imm-link" %s>',
					esc_url( $child->url ),
					$ariaAttrs
				);
			}

			if ( $showIcons && $icon && $iconPos === 'before' ) {
				$html .= $this->renderSingleIcon( $icon );
			}

			$html .= '<span class="imm-link-text">';
			$html .= sprintf(
				'<span class="imm-link-label">%s</span>',
				esc_html( $child->title )
			);

			if ( $showDescriptions && $desc ) {
				$html .= sprintf(
					'<span class="imm-link-desc">%s</span>',
					esc_html( $desc )
				);
			}

			$html .= '</span>';

			if ( $showIcons && $icon && $iconPos === 'after' ) {
				$html .= $this->renderSingleIcon( $icon );
			}

			if ( $badgeText ) {
				$badgeColor     = get_post_meta( $child->ID, '_imedia_menu_badge_color', true ) ?: '#e74c3c';
				$badgeTextColor = get_post_meta( $child->ID, '_imedia_menu_badge_text_color', true ) ?: '#ffffff';
				$badgePosition  = get_post_meta( $child->ID, '_imedia_menu_badge_position', true ) ?: 'inline';

				$html .= sprintf(
					'<span class="imm-badge imm-badge--%s" style="--imm-badge-bg:%s;--imm-badge-text:%s">%s</span>',
					esc_attr( $badgePosition ),
					esc_attr( $badgeColor ),
					esc_attr( $badgeTextColor ),
					esc_html( $badgeText )
				);
			}

			if ( $hasKids ) {
				$html .= '<span class="imm-caret" aria-hidden="true"></span>';
			}

			if ( $isDisabled ) {
				$html .= '</span>';
			} else {
				$html .= '</a>';
			}

			$html .= '</li>';
		}

		$html .= '</ul>';

		return $html;
	}

	private function renderSingleIcon( string $icon ): string {
		$parts    = explode( ':', $icon, 2 );
		$provider = $parts[0] ?? '';
		$iconId   = $parts[1] ?? '';

		if ( $provider === 'dashicons' ) {
			return sprintf(
				'<span class="imm-icon dashicons dashicons-%s" aria-hidden="true"></span>',
				esc_attr( $iconId )
			);
		}

		return sprintf(
			'<span class="imm-icon imm-icon--custom" aria-hidden="true" data-icon="%s"></span>',
			esc_attr( $icon )
		);
	}

	private function renderPlaceholder( bool $showDescriptions, bool $showIcons ): string {
		$html = '<ul class="imm-block imm-block--links" role="menu">';

		$html .= '<li role="none">';
		$html .= '<a href="#" role="menuitem" class="imm-link">';

		if ( $showIcons ) {
			$html .= '<span class="imm-link-icon dashicons dashicons-admin-links" aria-hidden="true"></span>';
		}

		$html .= '<span class="imm-link-text">';
		$html .= '<span class="imm-link-label">' . esc_html__( 'Link Item', 'imedia-menu' ) . '</span>';

		if ( $showDescriptions ) {
			$html .= '<span class="imm-link-desc">' . esc_html__( 'Link description', 'imedia-menu' ) . '</span>';
		}

		$html .= '</span></a></li>';

		$html .= '<li role="none">';
		$html .= '<a href="#" role="menuitem" class="imm-link">';
		$html .= '<span class="imm-link-text">';
		$html .= '<span class="imm-link-label">' . esc_html__( 'Another Link', 'imedia-menu' ) . '</span>';
		$html .= '</span></a></li>';

		$html .= '</ul>';

		return $html;
	}
}
