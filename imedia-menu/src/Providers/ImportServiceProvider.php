<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Import\MegamenuImporter;
use IMedia\Menu\Export\Exporter;
use IMedia\Menu\Export\Importer;

final class ImportServiceProvider implements ServiceProvider {

	public function register(): void {
	}

	public function boot(): void {
		add_action( 'wp_ajax_imedia_menu_import_megamenu', array( $this, 'ajaxImportMegamenu' ) );
		add_action( 'wp_ajax_imedia_menu_export', array( $this, 'ajaxExport' ) );
		add_action( 'wp_ajax_imedia_menu_import', array( $this, 'ajaxImport' ) );
	}

	public function ajaxImportMegamenu(): void {
		check_ajax_referer( 'imedia_menu_import_megamenu', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'imedia-menu' ) ), 403 );
		}

		$importer = new MegamenuImporter();
		$result   = $importer->import();

		if ( count( $result['errors'] ) > 0 ) {
			wp_send_json_error( $result, 400 );
		}

		wp_send_json_success( $result );
	}

	public function ajaxExport(): void {
		check_ajax_referer( 'imedia_menu_export', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'imedia-menu' ) ), 403 );
		}

		$exporter = new Exporter();
		$json     = $exporter->exportJson();

		wp_send_json_success( array( 'json' => $json ) );
	}

	public function ajaxImport(): void {
		check_ajax_referer( 'imedia_menu_import', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'imedia-menu' ) ), 403 );
		}

		$json = sanitize_text_field( wp_unslash( $_POST['json'] ?? '' ) );

		if ( $json === '' ) {
			wp_send_json_error( array( 'message' => __( 'No JSON data provided.', 'imedia-menu' ) ), 400 );
		}

		$importer = new Importer();
		$result   = $importer->importFromJson( $json );

		if ( count( $result['errors'] ) > 0 ) {
			wp_send_json_error( $result, 400 );
		}

		wp_send_json_success( $result );
	}
}
