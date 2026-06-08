<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class HtmlBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'html';
	}

	public function label(): string {
		return __( 'Custom HTML', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'content' => '',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'content' => array(
				'default'  => '',
				'sanitize' => 'wp_kses_post',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-html-' . wp_generate_uuid4();

		$attrs = array(
			'class'           => 'imm-toggle-block imm-toggle-block--html',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'html',
		);

		return '<div ' . $this->buildAttributes( $attrs ) . '>' . $s['content'] . '</div>';
	}
}
