<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

use IMedia\Menu\Database\PanelRepository;
use IMedia\Menu\Enums\MenuOrientation;
use IMedia\Menu\Enums\PanelLayoutType;
use IMedia\Menu\Frontend\MegaPanelRenderer;
use IMedia\Menu\Frontend\Assets;
use IMedia\Menu\Visibility\ConditionEvaluator;

final class MenuWalker extends \Walker_Nav_Menu {

	private PanelRepository $panelRepo;
	private MegaPanelRenderer $panelRenderer;
	private ConditionEvaluator $evaluator;
	private Assets $assets;

	/** @var array<int, object> */
	private array $panels = array();

	private int $menuId;

	private string $location;

	private string $animationType = 'fade';

	private string $triggerType = 'hover';

	private int $hoverDelay = 200;

	private MenuOrientation $orientation;

	public function __construct( int $menuId, ?array $settings = null, string $location = '' ) {
		$this->panelRepo     = new PanelRepository();
		$this->panelRenderer = new MegaPanelRenderer();
		$this->evaluator     = new ConditionEvaluator();
		$this->assets        = new Assets();
		$this->menuId        = $menuId;
		$this->location      = $location;

		$settings            = $settings ?? get_option( 'imedia_menu_settings', array() );
		$this->animationType = $settings['default_animation'] ?? 'fade';
		$this->triggerType   = $settings['trigger_type'] ?? 'hover';
		$this->hoverDelay    = (int) ( $settings['hover_delay'] ?? 200 );
		$this->orientation   = MenuOrientation::fromStringOrDefault( $settings['orientation'] ?? 'horizontal' );

		$forced = $this->orientation->requiredTriggerType();
		if ( $forced !== null ) {
			$this->triggerType = $forced;
		}
	}

	#[\ReturnTypeWillChange]
	public function walk( $elements, $max_depth, ...$args ) {
		$this->preloadPanels();

		return parent::walk( $elements, $max_depth, ...$args );
	}

	#[\ReturnTypeWillChange]
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( ! $this->evaluator->isItemVisible( $item ) ) {
			return;
		}

		$classes = $this->getItemClasses( $item, $depth );
		$hasMega = isset( $this->panels[ $item->ID ] );
		$hasKids = in_array( 'menu-item-has-children', $item->classes ?? array(), true );
		$isMega  = $hasMega && $this->panels[ $item->ID ]->is_enabled;

		if ( $isMega ) {
			$classes[] = 'imm-item--has-mega';
		} elseif ( $hasKids ) {
			$classes[] = 'imm-item--has-children';
		}

		$stickyVis = get_post_meta( $item->ID, '_imedia_menu_sticky_visibility', true );
		$stickyVis = ( is_string( $stickyVis ) && $stickyVis !== '' ) ? $stickyVis : 'always';
		if ( $stickyVis !== 'always' ) {
			$classes[] = 'imm-sticky-' . sanitize_html_class( $stickyVis );
		}

		$classes[] = 'imm-menu-item-' . (int) $item->ID;

		$styleOverrides = StyleOverrides::getItemStyles( (int) $item->ID );
		$styleAttr      = $styleOverrides !== '' ? ' style="' . esc_attr( $styleOverrides ) . '"' : '';

		$output .= sprintf(
			'<li class="%s" role="none"%s>',
			esc_attr( implode( ' ', $classes ) ),
			$styleAttr
		);

		$this->renderLink( $output, $item, $depth, $hasMega, $isMega, $hasKids );
	}

	#[\ReturnTypeWillChange]
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}

	#[\ReturnTypeWillChange]
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$classes = 'imm-sub';

		if ( $depth === 0 ) {
			$classes .= ' imm-sub--' . $this->orientation->value;
		} else {
			$classes .= ' imm-sub--sub';
		}

		$output .= sprintf(
			'<ul class="%s" role="menu" data-animation="%s" data-orientation="%s" hidden>',
			esc_attr( $classes ),
			esc_attr( $this->animationType ),
			esc_attr( $this->orientation->value )
		);
	}

	#[\ReturnTypeWillChange]
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= "</ul>\n";
	}

	#[\ReturnTypeWillChange]
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( isset( $this->panels[ $element->ID ] ) ) {
			$panel = $this->panels[ $element->ID ];

			if ( $panel->is_enabled ) {
				$layout = PanelLayoutType::fromStringOrDefault( $panel->layout_type ?? null );

				if ( $layout === PanelLayoutType::Flyout ) {
					$cssClass = $panel->css_class ?? '';
					if ( $cssClass !== '' ) {
						$element->classes[] = $cssClass;
					}

					parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
					return;
				}

				$this->displayMegaPanel( $element, $output, $depth );
				return;
			}
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	public function getOrientation(): MenuOrientation {
		return $this->orientation;
	}

	public function getLocation(): string {
		return $this->location;
	}

	public function getTriggerType(): string {
		return $this->triggerType;
	}

	private function preloadPanels(): void {
		$this->panels = array();

		$records = $this->panelRepo->findByMenu( $this->menuId );

		foreach ( $records as $record ) {
			$this->panels[ (int) $record->menu_item_id ] = $record;
		}
	}

	private function getItemClasses( object $item, int $depth ): array {
		$classes = array( 'imm-item' );

		$currentItemClasses = $item->classes ?? array();

		if ( in_array( 'current-menu-item', $currentItemClasses, true ) ) {
			$classes[] = 'imm-item--current';
		}

		if ( in_array( 'current-menu-parent', $currentItemClasses, true ) ) {
			$classes[] = 'imm-item--current-parent';
		}

		if ( in_array( 'current-menu-ancestor', $currentItemClasses, true ) ) {
			$classes[] = 'imm-item--current-ancestor';
		}

		if ( $depth > 0 ) {
			$classes[] = 'imm-item--sub';
		}

		$custom = get_post_meta( $item->ID, '_menu_item_classes', true );
		if ( ! empty( $custom ) && is_array( $custom ) ) {
			$classes = array_merge( $classes, array_filter( $custom ) );
		}

		return array_unique( $classes );
	}

	private function renderLink( string &$output, object $item, int $depth, bool $hasMega, bool $isMega, bool $hasKids ): void {
		$isDisabled = (bool) get_post_meta( $item->ID, '_imedia_menu_disable_link', true );
		$icon       = get_post_meta( $item->ID, '_imedia_menu_icon', true );
		$iconPos    = get_post_meta( $item->ID, '_imedia_menu_icon_position', true );
		$iconPos    = ( is_string( $iconPos ) && $iconPos !== '' ) ? $iconPos : 'before';
		$desc       = get_post_meta( $item->ID, '_imedia_menu_description', true );

		$hasPopup = $isMega || $hasKids;
		$attrs    = array(
			'class' => 'imm-link',
			'role'  => 'menuitem',
		);

		if ( $hasPopup ) {
			$attrs['aria-haspopup'] = 'true';
			$attrs['aria-expanded'] = 'false';

			if ( $isMega ) {
				$attrs['aria-controls'] = "imm-panel-{$item->ID}";
			}
		}

		if ( in_array( 'current-menu-item', $item->classes ?? array(), true ) ) {
			$attrs['aria-current'] = 'page';
		}

		if ( $isDisabled ) {
			$attrs['aria-disabled'] = 'true';
			$attrs['tabindex']      = '-1';
			$attrs['class']        .= ' imm-link--disabled';

			$output .= sprintf(
				'<span %s>',
				$this->buildAttributes( $attrs )
			);
		} else {
			$attrs['href'] = $item->url;

			$output .= sprintf(
				'<a %s>',
				$this->buildAttributes( $attrs )
			);
		}

		if ( $icon && $iconPos === 'before' ) {
			$output .= $this->renderIcon( $icon, $iconPos );
		}

		$output .= sprintf(
			'<span class="imm-label">%s</span>',
			esc_html( $item->title )
		);

		if ( $icon && $iconPos === 'after' ) {
			$output .= $this->renderIcon( $icon, $iconPos );
		}

		$badge = Badge::render( (int) $item->ID );
		if ( $badge !== '' ) {
			$output .= $badge;
		}

		if ( $desc ) {
			$output .= sprintf(
				'<span class="imm-desc">%s</span>',
				esc_html( $desc )
			);
		}

		if ( $hasPopup ) {
			$output .= '<span class="imm-caret" aria-hidden="true"></span>';
		}

		if ( $isDisabled ) {
			$output .= '</span>';
		} else {
			$output .= '</a>';
		}
	}

	private function renderIcon( string $icon, string $position ): string {
		$parts    = explode( ':', $icon, 2 );
		$provider = $parts[0] ?? '';
		$iconId   = $parts[1] ?? '';

		if ( $provider === 'dashicons' ) {
			return sprintf(
				'<span class="imm-icon imm-icon--%s dashicons dashicons-%s" aria-hidden="true"></span>',
				esc_attr( $position ),
				esc_attr( $iconId )
			);
		}

		return sprintf(
			'<span class="imm-icon imm-icon--%s imm-icon--custom" aria-hidden="true" data-icon="%s"></span>',
			esc_attr( $position ),
			esc_attr( $icon )
		);
	}

	private function displayMegaPanel( object $element, string &$output, int $depth ): void {
		$panel     = $this->panels[ $element->ID ];
		$panelHtml = $this->panelRenderer->render( $panel );

		$this->renderLink( $output, $element, $depth, true, true, false );

		$panelWidthClass = 'imm-panel--' . $panel->panel_width;

		$output .= sprintf(
			'<div id="imm-panel-%d" class="imm-panel %s" role="menu" aria-label="%s" data-animation="%s" hidden>',
			(int) $element->ID,
			esc_attr( $panelWidthClass ),
			esc_attr( $element->title ),
			esc_attr( $panel->animation_type ?: $this->animationType )
		);

		$output .= sprintf(
			'<div class="imm-panel-inner"%s>',
			$this->getPanelStyles( $panel )
		);

		$output .= $panelHtml;

		$output .= '</div></div>';
	}

	private function getPanelStyles( object $panel ): string {
		if ( empty( $panel->styles ) ) {
			return '';
		}

		$styles = $panel->styles;
		$css    = array();

		if ( isset( $styles['padding'] ) ) {
			$p     = $styles['padding'];
			$css[] = "padding:{$p['top']} {$p['right']} {$p['bottom']} {$p['left']}";
		}

		if ( isset( $styles['background']['color'] ) ) {
			$css[] = '--imm-panel-bg:' . $styles['background']['color'];
		}

		if ( isset( $styles['border']['radius'] ) ) {
			$css[] = 'border-radius:' . $styles['border']['radius'];
		}

		if ( isset( $styles['shadow']['color'] ) ) {
			$s     = $styles['shadow'];
			$css[] = "box-shadow:{$s['offsetX']} {$s['offsetY']} {$s['blur']} {$s['spread']} {$s['color']}";
		}

		if ( empty( $css ) ) {
			return '';
		}

		return ' style="' . esc_attr( implode( ';', $css ) ) . '"';
	}

	private function buildAttributes( array $attrs ): string {
		$parts = array();

		foreach ( $attrs as $key => $value ) {
			if ( is_bool( $value ) ) {
				if ( $value ) {
					$parts[] = esc_attr( $key );
				}
			} else {
				$parts[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}

		return implode( ' ', $parts );
	}
}
