<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class DynamicHtmlBlock implements ContentBlock {

	private const CACHE_GROUP      = 'imedia_menu';
	private const CACHE_KEY_PREFIX = 'imedia_menu_dyn_html_';

	public function type(): string {
		return 'dynamic_html';
	}

	public function title(): string {
		return __( 'Dynamic HTML', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'source'       => 'url',
			'url'          => '',
			'callback'     => '',
			'method'       => 'GET',
			'cache_ttl'    => 300,
			'timeout'      => 5,
			'allowed_html' => array(),
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$source = $config['source'] ?? 'url';

		if ( $source === 'url' ) {
			$html = $this->fetchFromUrl( $config );
		} elseif ( $source === 'callback' ) {
			$html = $this->invokeCallback( $config );
		} else {
			return $this->renderError( __( 'Unknown source type', 'imedia-menu' ) );
		}

		if ( $html === null ) {
			return $this->renderError( __( 'Source returned no content', 'imedia-menu' ) );
		}

		$allowed = $this->resolveAllowedHtml( $config['allowed_html'] ?? array() );

		return sprintf(
			'<div class="imm-block imm-block--dynamic-html" data-source="%s">%s</div>',
			esc_attr( $source ),
			wp_kses( $html, $allowed )
		);
	}

	private function fetchFromUrl( array $config ): ?string {
		$url = (string) ( $config['url'] ?? '' );

		if ( $url === '' ) {
			return null;
		}

		$safeUrl = esc_url_raw( $url );
		if ( $safeUrl === '' ) {
			return null;
		}

		$cacheKey = self::CACHE_KEY_PREFIX . md5( 'url:' . $safeUrl );
		$cached   = wp_cache_get( $cacheKey, self::CACHE_GROUP );

		if ( is_string( $cached ) && $cached !== '' ) {
			return $cached;
		}

		$timeout  = max( 1, min( 30, (int) ( $config['timeout'] ?? 5 ) ) );
		$response = wp_remote_get( $safeUrl, array( 'timeout' => $timeout ) );
		$body     = is_array( $response ) ? wp_remote_retrieve_body( $response ) : '';

		if ( ! is_string( $body ) || $body === '' ) {
			return null;
		}

		$ttl = max( 0, (int) ( $config['cache_ttl'] ?? 300 ) );
		wp_cache_set( $cacheKey, $body, self::CACHE_GROUP, $ttl );

		return $body;
	}

	private function invokeCallback( array $config ): ?string {
		$callback = $config['callback'] ?? '';

		if ( ! is_callable( $callback ) ) {
			return null;
		}

		if ( is_string( $callback ) ) {
			$cacheKey = self::CACHE_KEY_PREFIX . md5( 'callback:' . $callback );
		} else {
			$cacheKey = self::CACHE_KEY_PREFIX . md5( 'callback:' . wp_json_encode( $callback ) );
		}
		$cached = wp_cache_get( $cacheKey, self::CACHE_GROUP );

		if ( is_string( $cached ) && $cached !== '' ) {
			return $cached;
		}

		$output = (string) call_user_func( $callback );

		if ( $output === '' ) {
			return null;
		}

		$ttl = max( 0, (int) ( $config['cache_ttl'] ?? 300 ) );
		wp_cache_set( $cacheKey, $output, self::CACHE_GROUP, $ttl );

		return $output;
	}

	private function resolveAllowedHtml( $allowed ): array {
		if ( is_array( $allowed ) && ! empty( $allowed ) ) {
			return $allowed;
		}
		return wp_kses_allowed_html( 'post' );
	}

	private function renderError( string $message ): string {
		return sprintf(
			'<div class="imm-block imm-block--dynamic-html imm-block--unavailable"><p class="imm-empty">%s</p></div>',
			esc_html( $message )
		);
	}
}
