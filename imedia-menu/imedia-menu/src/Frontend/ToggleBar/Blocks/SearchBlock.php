<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\AbstractToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class SearchBlock extends AbstractToggleBlock implements ToggleBlock {

	public function type(): string {
		return 'search';
	}

	public function label(): string {
		return __( 'Search', 'imedia-menu' );
	}

	public function defaultSettings(): array {
		return array(
			'placeholder' => __( 'Search...', 'imedia-menu' ),
			'action'      => home_url( '/' ),
			'method'      => 'get',
		);
	}

	public function validate( array $settings ): array {
		$schema = array(
			'placeholder' => array(
				'default'  => __( 'Search...', 'imedia-menu' ),
				'sanitize' => 'sanitize_text_field',
			),
			'action'      => array(
				'default'  => home_url( '/' ),
				'sanitize' => 'esc_url_raw',
			),
			'method'      => array(
				'default'  => 'get',
				'sanitize' => fn( $v ) => in_array( strtolower( $v ), array( 'get', 'post' ), true ) ? strtolower( $v ) : 'get',
			),
		);
		return $this->sanitizeSettings( $settings, $schema );
	}

	public function render( array $settings, array $args ): string {
		$s  = $this->validate( $settings );
		$id = $args['block_id'] ?? 'imm-search-' . wp_generate_uuid4();

		$attrs = array(
			'class'              => 'imm-toggle-block imm-toggle-block--search',
			'id'                 => $id,
			'data-block-id'      => $args['block_id'] ?? '',
			'data-block-type'    => 'search',
			'data-search-action' => esc_attr( $s['action'] ),
		);

		$icon  = '<svg class="imm-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
		$input = '<input type="search" class="imm-search-input" placeholder="' . esc_attr( $s['placeholder'] ) . '" name="s" value="' . esc_attr( get_search_query() ) . '" aria-label="' . esc_attr( $s['placeholder'] ) . '" />';
		$form  = '<form class="imm-search-form" action="' . esc_url( $s['action'] ) . '" method="' . esc_attr( $s['method'] ) . '" role="search">' . $input . '</form>';

		return '<div ' . $this->buildAttributes( $attrs ) . '>' . $icon . $form . '</div>';
	}

	public function requiredStylesheet(): ?string {
		return 'imm-toggle-bar';
	}

	public function requiredScript(): ?string {
		return 'imm-toggle-bar';
	}
}
