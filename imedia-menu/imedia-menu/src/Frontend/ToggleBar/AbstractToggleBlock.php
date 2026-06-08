<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

abstract class AbstractToggleBlock implements ToggleBlock {

	public function requiredStylesheet(): ?string {
		return null;
	}

	public function requiredScript(): ?string {
		return null;
	}

	protected function sanitizeSettings( array $settings, array $schema ): array {
		$sanitized = array();
		foreach ( $schema as $key => $spec ) {
			$value = $settings[ $key ] ?? $spec['default'] ?? null;
			if ( isset( $spec['sanitize'] ) && is_callable( $spec['sanitize'] ) ) {
				$value = $spec['sanitize']( $value );
			}
			$sanitized[ $key ] = $value;
		}
		return $sanitized;
	}

	protected function buildAttributes( array $attrs ): string {
		$out = '';
		foreach ( $attrs as $key => $value ) {
			if ( $value === null || $value === false || $value === '' ) {
				continue;
			}
			if ( is_bool( $value ) && $value === true ) {
				$out .= ' ' . esc_attr( $key );
			} else {
				$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
			}
		}
		return $out;
	}
}
