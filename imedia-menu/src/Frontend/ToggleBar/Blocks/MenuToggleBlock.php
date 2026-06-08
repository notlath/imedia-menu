<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class MenuToggleBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'menu_toggle';
	}

	public function label(): string {
		return __( 'Menu Toggle', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'label'       => __( 'Menu', 'imedia-menu' ),
			'icon_only'   => false,
			'closed_text' => '',
			'open_text'   => '',
			'aria_label'  => __( 'Toggle navigation menu', 'imedia-menu' ),
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'label'       => array(
				'default'  => __( 'Menu', 'imedia-menu' ),
				'sanitize' => 'sanitize_text_field',
			),
			'icon_only'   => array(
				'default'  => false,
				'sanitize' => fn( $v ) => (bool) $v,
			),
			'closed_text' => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
			'open_text'   => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
			'aria_label'  => array(
				'default'  => __( 'Toggle navigation menu', 'imedia-menu' ),
				'sanitize' => 'sanitize_text_field',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-toggle-' . wp_generate_uuid4();

		$label     = $s['icon_only'] ? '' : $s['label'];
		$ariaLabel = $s['aria_label'];

		$attrs = array(
			'type'            => 'button',
			'class'           => 'imm-toggle-block imm-toggle-block--menu-toggle',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'menu_toggle',
			'aria-label'      => $ariaLabel,
			'aria-expanded'   => 'false',
			'aria-controls'   => 'imm-mobile-nav',
		);

		$bars      = '<span class="imm-hamburger" aria-hidden="true"><span></span><span></span><span></span></span>';
		$labelHtml = $label ? '<span class="imm-toggle-label">' . esc_html( $label ) . '</span>' : '';

		return '<button ' . $this->buildAttributes( $attrs ) . '>' . $bars . $labelHtml . '</button>';
	}
}
