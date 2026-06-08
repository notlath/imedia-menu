<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class IconBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'icon';
	}

	public function label(): string {
		return __( 'Icon', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'icon'       => '',
			'url'        => '',
			'target'     => '_self',
			'aria_label' => '',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'icon'       => array(
				'default'  => '',
				'sanitize' => 'wp_kses_post',
			),
			'url'        => array(
				'default'  => '',
				'sanitize' => 'esc_url_raw',
			),
			'target'     => array(
				'default'  => '_self',
				'sanitize' => fn( $v ) => in_array( $v, array( '_self', '_blank' ), true ) ? $v : '_self',
			),
			'aria_label' => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-icon-' . wp_generate_uuid4();

		$iconHtml = '';
		if ( $s['icon'] ) {
			if ( str_starts_with( $s['icon'], 'dashicons-' ) ) {
				$iconHtml = '<span class="dashicons ' . esc_attr( $s['icon'] ) . '" aria-hidden="true"></span>';
			} else {
				$iconHtml = '<span class="imm-custom-icon" aria-hidden="true">' . wp_kses_post( $s['icon'] ) . '</span>';
			}
		}

		$content = $iconHtml;
		if ( $s['url'] ) {
			$rel     = $s['target'] === '_blank' ? 'noopener noreferrer' : '';
			$content = '<a href="' . esc_url( $s['url'] ) . '" target="' . esc_attr( $s['target'] ) . '" rel="' . esc_attr( $rel ) . '" aria-label="' . esc_attr( $s['aria_label'] ) . '">' . $iconHtml . '</a>';
		}

		$attrs = array(
			'class'           => 'imm-toggle-block imm-toggle-block--icon',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'icon',
			'aria-label'      => ! empty( $s['aria_label'] ) ? $s['aria_label'] : null,
		);

		return '<div ' . $this->buildAttributes( $attrs ) . '>' . $content . '</div>';
	}
}
