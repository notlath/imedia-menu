<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class TabbedBlock implements ContentBlock {

	/**
	 * Block registry for rendering child blocks within tabs.
	 *
	 * @var Registry|null
	 */
	private ?Registry $registry = null;

	public function setRegistry( Registry $registry ): void {
		$this->registry = $registry;
	}

	public function type(): string {
		return 'tabbed';
	}

	public function title(): string {
		return __( 'Tabs', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'tabs'        => array(),
			'orientation' => 'horizontal',
			'default_tab' => '',
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$tabs        = is_array( $config['tabs'] ?? null ) ? $config['tabs'] : array();
		$orientation = in_array( $config['orientation'] ?? 'horizontal', array( 'horizontal', 'vertical' ), true )
			? ( $config['orientation'] ?? 'horizontal' )
			: 'horizontal';
		$defaultTab  = (string) ( $config['default_tab'] ?? '' );

		if ( empty( $tabs ) ) {
			return sprintf(
				'<div class="imm-block imm-block--tabbed imm-block--empty"><p class="imm-empty">%s</p></div>',
				esc_html__( 'No tabs defined', 'imedia-menu' )
			);
		}

		$firstId  = isset( $tabs[0]['id'] ) ? (string) $tabs[0]['id'] : '';
		$activeId = $defaultTab !== '' ? $defaultTab : $firstId;

		$html = sprintf(
			'<div class="imm-block imm-block--tabbed imm-block--tabbed--%s" data-default-tab="%s" data-tab-id="%s">',
			esc_attr( $orientation ),
			esc_attr( $activeId ),
			esc_attr( wp_generate_uuid4() ? wp_generate_uuid4() : 'imm-tabs' )
		);

		$html .= sprintf(
			'<ul class="imm-tablist" role="tablist" aria-orientation="%s">',
			esc_attr( $orientation )
		);

		foreach ( $tabs as $index => $tab ) {
			if ( ! is_array( $tab ) ) {
				continue;
			}

			$tabId    = isset( $tab['id'] ) ? (string) $tab['id'] : 'tab-' . $index;
			$label    = isset( $tab['label'] ) ? (string) $tab['label'] : '';
			$selected = $tabId === $activeId;

			$html .= sprintf(
				'<li role="presentation"><button type="button" role="tab" id="tab-%1$s" aria-controls="panel-%1$s" aria-selected="%2$s" tabindex="%3$s">%4$s</button></li>',
				esc_attr( $tabId ),
				$selected ? 'true' : 'false',
				$selected ? '0' : '-1',
				esc_html( $label )
			);
		}

		$html .= '</ul>';

		foreach ( $tabs as $index => $tab ) {
			if ( ! is_array( $tab ) ) {
				continue;
			}

			$tabId       = isset( $tab['id'] ) ? (string) $tab['id'] : 'tab-' . $index;
			$isActive    = $tabId === $activeId;
			$childBlocks = is_array( $tab['blocks'] ?? null ) ? $tab['blocks'] : array();

			$childHtml = '';
			if ( $this->registry !== null ) {
				foreach ( $childBlocks as $childBlock ) {
					if ( is_array( $childBlock ) && isset( $childBlock['type'] ) ) {
						$childHtml .= $this->registry->render( $childBlock );
					}
				}
			}

			$html .= sprintf(
				'<div role="tabpanel" id="panel-%1$s" aria-labelledby="tab-%1$s"%2$s>%3$s</div>',
				esc_attr( $tabId ),
				$isActive ? '' : ' hidden',
				$childHtml
			);
		}

		$html .= '</div>';

		return $html;
	}
}
