<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class CustomBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'custom';
	}

	public function label(): string {
		return __( 'Custom / Shortcode', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'shortcode' => '',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'shortcode' => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-custom-' . wp_generate_uuid4();

		$content = '';
		if ( $s['shortcode'] ) {
			$content = do_shortcode( $s['shortcode'] );
		}

		$attrs = array(
			'class'           => 'imm-toggle-block imm-toggle-block--custom',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'custom',
		);

		return '<div ' . $this->buildAttributes( $attrs ) . '>' . $content . '</div>';
	}
}
