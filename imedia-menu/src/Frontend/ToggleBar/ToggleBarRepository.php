<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar;

final class ToggleBarRepository {

	public const OPTION_KEY = 'imedia_menu_toggle_bar';

	private const VALID_TYPES = array(
		'menu_toggle',
		'menu_toggle_animated',
		'spacer',
		'search',
		'logo',
		'icon',
		'html',
		'custom',
	);

	private const VALID_ALIGNS = array( 'left', 'center', 'right' );

	public function __construct() {
		$this->ensureOptionInitialized();
	}

	private function ensureOptionInitialized(): void {
		if ( get_option( self::OPTION_KEY ) === false ) {
			add_option( self::OPTION_KEY, array() );
		}
	}

	public function get( string $location ): array {
		$all = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $all ) ) {
			return array();
		}

		return $all[ $location ]['blocks'] ?? array();
	}

	public function save( string $location, array $blocks ): bool {
		$validatedBlocks = array();

		foreach ( $blocks as $block ) {
			$validated = $this->validateBlock( $block );
			if ( $validated ) {
				$validatedBlocks[] = $validated;
			}
		}

		$all = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $all ) ) {
			$all = array();
		}

		if ( empty( $validatedBlocks ) ) {
			unset( $all[ $location ] );
		} else {
			$all[ $location ] = array( 'blocks' => $validatedBlocks );
		}

		return update_option( self::OPTION_KEY, $all );
	}

	public function delete( string $location ): bool {
		$all = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $all ) || ! isset( $all[ $location ] ) ) {
			return true;
		}

		unset( $all[ $location ] );

		return update_option( self::OPTION_KEY, $all );
	}

	public function hasBlocks( string $location ): bool {
		$blocks = $this->get( $location );

		return ! empty( $blocks );
	}

	public function getRaw( string $location ): array {
		$all = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $all ) ) {
			return array();
		}

		return $all[ $location ] ?? array();
	}

	public function anyLocationHasBlocks(): bool {
		$all = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $all ) ) {
			return false;
		}

		foreach ( $all as $locationData ) {
			if ( is_array( $locationData ) && ! empty( $locationData['blocks'] ?? null ) ) {
				return true;
			}
		}

		return false;
	}

	public function getAll(): array {
		$all = get_option( self::OPTION_KEY, array() );

		return is_array( $all ) ? $all : array();
	}

	private function validateBlock( array $block ): ?array {
		if ( ! isset( $block['id'] ) || ! is_string( $block['id'] ) ) {
			return null;
		}

		$type = $block['type'] ?? '';
		if ( ! in_array( $type, self::VALID_TYPES, true ) ) {
			return null;
		}

		$align = $block['align'] ?? 'left';
		if ( ! in_array( $align, self::VALID_ALIGNS, true ) ) {
			$align = 'left';
		}

		$settings = $block['settings'] ?? array();

		return array(
			'id'       => $block['id'],
			'type'     => $type,
			'align'    => $align,
			'settings' => $this->validateSettings( $type, $settings ),
		);
	}

	private function validateSettings( string $type, array $settings ): array {
		switch ( $type ) {
			case 'menu_toggle':
				return array(
					'label'       => sanitize_text_field( $settings['label'] ?? __( 'Menu', 'imedia-menu' ) ),
					'icon_only'   => (bool) ( $settings['icon_only'] ?? false ),
					'closed_text' => sanitize_text_field( $settings['closed_text'] ?? '' ),
					'open_text'   => sanitize_text_field( $settings['open_text'] ?? '' ),
					'aria_label'  => sanitize_text_field( $settings['aria_label'] ?? __( 'Toggle navigation menu', 'imedia-menu' ) ),
				);

			case 'menu_toggle_animated':
				$animation = $settings['animation'] ?? 'arrow';
				if ( ! in_array( $animation, array( 'arrow', 'slider' ), true ) ) {
					$animation = 'arrow';
				}
				return array(
					'animation'   => $animation,
					'label'       => sanitize_text_field( $settings['label'] ?? __( 'Menu', 'imedia-menu' ) ),
					'icon_only'   => (bool) ( $settings['icon_only'] ?? false ),
					'closed_text' => sanitize_text_field( $settings['closed_text'] ?? '' ),
					'open_text'   => sanitize_text_field( $settings['open_text'] ?? '' ),
					'aria_label'  => sanitize_text_field( $settings['aria_label'] ?? __( 'Toggle navigation menu', 'imedia-menu' ) ),
				);

			case 'spacer':
				return array(
					'width' => sanitize_text_field( $settings['width'] ?? '20px' ),
				);

			case 'search':
				return array(
					'placeholder' => sanitize_text_field( $settings['placeholder'] ?? __( 'Search...', 'imedia-menu' ) ),
					'action'      => esc_url_raw( $settings['action'] ?? home_url( '/' ) ),
					'method'      => 'get',
				);

			case 'logo':
				return array(
					'logo_id' => absint( $settings['logo_id'] ?? 0 ),
					'url'     => esc_url_raw( $settings['url'] ?? home_url( '/' ) ),
					'target'  => in_array( $settings['target'] ?? '_self', array( '_self', '_blank' ), true ) ? $settings['target'] : '_self',
					'alt'     => sanitize_text_field( $settings['alt'] ?? '' ),
				);

			case 'icon':
				return array(
					'icon'       => sanitize_text_field( $settings['icon'] ?? '' ),
					'url'        => esc_url_raw( $settings['url'] ?? '' ),
					'target'     => in_array( $settings['target'] ?? '_self', array( '_self', '_blank' ), true ) ? $settings['target'] : '_self',
					'aria_label' => sanitize_text_field( $settings['aria_label'] ?? '' ),
				);

			case 'html':
				return array(
					'content' => wp_kses_post( $settings['content'] ?? '' ),
				);

			case 'custom':
				return array(
					'shortcode' => sanitize_text_field( $settings['shortcode'] ?? '' ),
				);

			default:
				return array();
		}
	}

	public static function getValidTypes(): array {
		return self::VALID_TYPES;
	}

	public static function getValidAligns(): array {
		return self::VALID_ALIGNS;
	}
}
