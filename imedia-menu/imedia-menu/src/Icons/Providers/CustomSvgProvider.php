<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons\Providers;

use IMedia\Menu\Contracts\IconProvider;

final class CustomSvgProvider implements IconProvider {

	public function id(): string {
		return 'svg';
	}

	public function name(): string {
		return __( 'Custom SVG', 'imedia-menu' );
	}

	public function getIcon( string $identifier ): string {
		$attachmentId = (int) $identifier;

		if ( $attachmentId <= 0 ) {
			return '';
		}

		$url = wp_get_attachment_url( $attachmentId );

		if ( ! $url ) {
			return '';
		}

		$mime = get_post_mime_type( $attachmentId );

		if ( $mime === 'image/svg+xml' ) {
			$svgContent = file_get_contents( get_attached_file( $attachmentId ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			if ( $svgContent !== false ) {
				$svgContent = $this->sanitizeSvg( $svgContent );
				return $svgContent;
			}
		}

		return sprintf(
			'<img class="imm-icon imm-icon--svg" src="%s" alt="" aria-hidden="true" />',
			esc_url( $url )
		);
	}

	public function getAvailableIcons(): array {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, post_title FROM {$wpdb->posts}
                WHERE post_type = 'attachment'
                AND post_mime_type = 'image/svg+xml'
                AND post_status = 'inherit'
                ORDER BY post_title ASC
                LIMIT 200"
			)
		);

		$icons = array();

		foreach ( $results as $row ) {
			$icons[ (string) $row->ID ] = $row->post_title ?: sprintf( '#%d', $row->ID );
		}

		return $icons;
	}

	public function uploadSvg( string $filePath ): ?int {
		$filename = basename( $filePath );
		$contents = file_get_contents( $filePath ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( $contents === false ) {
			return null;
		}

		$sanitized = $this->sanitizeSvg( $contents );

		$upload = wp_upload_bits( $filename, null, $sanitized );

		if ( ! empty( $upload['error'] ) ) {
			return null;
		}

		$attachmentId = wp_insert_attachment(
			array(
				'post_title'     => pathinfo( $filename, PATHINFO_FILENAME ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image/svg+xml',
			),
			$upload['file']
		);

		if ( ! $attachmentId ) {
			return null;
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_generate_attachment_metadata( $attachmentId, $upload['file'] );

		return $attachmentId;
	}

	private function sanitizeSvg( string $svg ): string {
		$svg = preg_replace( '/<script[^>]*>.*?<\/script>/si', '', $svg );
		$svg = preg_replace( '/on\w+="[^"]*"/i', '', $svg );
		$svg = preg_replace( '/on\w+=\'[^\']*\'/i', '', $svg );
		$svg = preg_replace( '/on\w+=\w+/i', '', $svg );
		$svg = preg_replace( '/<foreignObject[^>]*>.*?<\/foreignObject>/si', '', $svg );

		return $svg;
	}
}
