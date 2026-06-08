<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class SpacerBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'spacer';
	}

	public function label(): string {
		return __( 'Spacer', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'width' => '20px',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'width' => array(
				'default'  => '20px',
				'sanitize' => 'sanitize_text_field',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-spacer-' . wp_generate_uuid4();

		$attrs = array(
			'class'           => 'imm-toggle-block imm-toggle-block--spacer',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'spacer',
			'style'           => 'width:' . esc_attr( $s['width'] ) . ';flex-shrink:0;',
			'aria-hidden'     => 'true',
		);

		return '<div ' . $this->buildAttributes( $attrs ) . '></div>';
	}
}
