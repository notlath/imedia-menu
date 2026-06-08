<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class AnimatedMenuToggleBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'menu_toggle_animated';
	}

	public function label(): string {
		return __( 'Animated Menu Toggle', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'animation'   => 'arrow',
			'label'       => __( 'Menu', 'imedia-menu' ),
			'icon_only'   => false,
			'closed_text' => '',
			'open_text'   => '',
			'aria_label'  => __( 'Toggle navigation menu', 'imedia-menu' ),
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'animation'   => array(
				'default'  => 'arrow',
				'sanitize' => fn( $v ) => in_array( $v, array( 'arrow', 'slider' ), true ) ? $v : 'arrow',
			),
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
		$id = $args['block_id'] ?? 'imm-toggle-anim-' . wp_generate_uuid4();

		$label     = $s['icon_only'] ? '' : $s['label'];
		$ariaLabel = $s['aria_label'];
		$animation = $s['animation'];

		$attrs = array(
			'type'            => 'button',
			'class'           => 'imm-toggle-block imm-toggle-block--menu-toggle-animated imm-toggle-anim--' . esc_attr( $animation ),
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'menu_toggle_animated',
			'data-animation'  => $animation,
			'aria-label'      => $ariaLabel,
			'aria-expanded'   => 'false',
			'aria-controls'   => 'imm-mobile-nav',
		);

		if ( $animation === 'arrow' ) {
			$bars = '<span class="imm-toggle-anim-bars" aria-hidden="true"><span></span><span></span><span></span></span>';
		} else {
			$bars = '<span class="imm-toggle-anim-slider" aria-hidden="true"><span></span></span>';
		}
		$labelHtml = $label ? '<span class="imm-toggle-label">' . esc_html( $label ) . '</span>' : '';

		return '<button ' . $this->buildAttributes( $attrs ) . '>' . $bars . $labelHtml . '</button>';
	}

	public function requiredStylesheet(): ?string {
		return 'imm-toggle-bar';
	}

	public function requiredScript(): ?string {
		return 'imm-toggle-bar';
	}
}
