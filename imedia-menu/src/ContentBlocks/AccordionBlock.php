<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class AccordionBlock implements ContentBlock {

	/**
	 * Block registry for rendering child blocks within accordion items.
	 *
	 * @var Registry|null
	 */
	private ?Registry $registry = null;

	public function setRegistry( Registry $registry ): void {
		$this->registry = $registry;
	}

	public function type(): string {
		return 'accordion';
	}

	public function title(): string {
		return __( 'Accordion', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'items'            => array(),
			'multi_open'       => false,
			'allow_toggle_all' => false,
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$items     = is_array( $config['items'] ?? null ) ? $config['items'] : array();
		$multiOpen = ! empty( $config['multi_open'] );

		if ( empty( $items ) ) {
			return sprintf(
				'<div class="imm-block imm-block--accordion imm-block--empty" data-multi-open="%s"><p class="imm-empty">%s</p></div>',
				$multiOpen ? '1' : '0',
				esc_html__( 'No accordion items', 'imedia-menu' )
			);
		}

		$html = sprintf(
			'<div class="imm-block imm-block--accordion" data-multi-open="%s">',
			$multiOpen ? '1' : '0'
		);

		foreach ( $items as $index => $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$id            = isset( $item['id'] ) ? (string) $item['id'] : 'acc-' . $index;
			$label         = isset( $item['label'] ) ? (string) $item['label'] : '';
			$initiallyOpen = ! empty( $item['initially_open'] );
			$childBlocks   = is_array( $item['blocks'] ?? null ) ? $item['blocks'] : array();

			$childHtml = '';
			if ( $this->registry !== null ) {
				foreach ( $childBlocks as $childBlock ) {
					if ( is_array( $childBlock ) && isset( $childBlock['type'] ) ) {
						$childHtml .= $this->registry->render( $childBlock );
					}
				}
			}

			$html .= sprintf(
				'<details id="%s" class="imm-accordion__item"%s>',
				esc_attr( $id ),
				$initiallyOpen ? ' open' : ''
			);
			$html .= sprintf( '<summary class="imm-accordion__label">%s</summary>', esc_html( $label ) );
			$html .= sprintf( '<div class="imm-accordion__content">%s</div>', $childHtml );
			$html .= '</details>';
		}

		$html .= '</div>';

		return $html;
	}
}
