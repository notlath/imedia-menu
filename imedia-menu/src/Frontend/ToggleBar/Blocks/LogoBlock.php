<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class LogoBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'logo';
	}

	public function label(): string {
		return __( 'Logo', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'logo_id' => 0,
			'url'     => home_url( '/' ),
			'target'  => '_self',
			'alt'     => '',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'logo_id' => array(
				'default'  => 0,
				'sanitize' => 'absint',
			),
			'url'     => array(
				'default'  => home_url( '/' ),
				'sanitize' => 'esc_url_raw',
			),
			'target'  => array(
				'default'  => '_self',
				'sanitize' => fn( $v ) => in_array( $v, array( '_self', '_blank' ), true ) ? $v : '_self',
			),
			'alt'     => array(
				'default'  => '',
				'sanitize' => 'sanitize_text_field',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-logo-' . wp_generate_uuid4();

		$img = '';
		if ( $s['logo_id'] > 0 ) {
			$imgUrl = wp_get_attachment_image_url( $s['logo_id'], 'full' );
			if ( $imgUrl ) {
				$img = '<img src="' . esc_url( $imgUrl ) . '" alt="' . esc_attr( $s['alt'] ) . '" class="imm-logo-img" />';
			}
		}

		if ( ! $img ) {
			$img = '<span class="imm-logo-placeholder" aria-hidden="true">' . esc_html__( 'Logo', 'imedia-menu' ) . '</span>';
		}

		$attrs = array(
			'class'           => 'imm-toggle-block imm-toggle-block--logo',
			'id'              => $id,
			'data-block-id'   => $args['block_id'] ?? '',
			'data-block-type' => 'logo',
		);

		$content = $s['url'] ? '<a href="' . esc_url( $s['url'] ) . '" target="' . esc_attr( $s['target'] ) . '" rel="' . ( $s['target'] === '_blank' ? 'noopener noreferrer' : '' ) . '">' . $img . '</a>' : $img;

		return '<div ' . $this->buildAttributes( $attrs ) . '>' . $content . '</div>';
	}
}
