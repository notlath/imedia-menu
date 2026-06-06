<?php

declare(strict_types=1);

namespace IMedia\Menu\Export;

use IMedia\Menu\Database\PanelRepository;
use IMedia\Menu\Database\TemplateRepository;

final class Importer {

	private PanelRepository $panelRepo;
	private TemplateRepository $templateRepo;

	public function __construct() {
		$this->panelRepo    = new PanelRepository();
		$this->templateRepo = new TemplateRepository();
	}

	public function import( array $data ): array {
		$results = array(
			'settings'  => false,
			'panels'    => 0,
			'templates' => 0,
			'errors'    => array(),
		);

		if ( ! isset( $data['version'] ) ) {
			$results['errors'][] = __( 'Missing version field in import data.', 'imedia-menu' );
			return $results;
		}

		if ( isset( $data['settings'] ) && is_array( $data['settings'] ) ) {
			update_option( 'imedia_menu_settings', $data['settings'] );
			$results['settings'] = true;
		}

		if ( isset( $data['panels'] ) && is_array( $data['panels'] ) ) {
			foreach ( $data['panels'] as $panel ) {
				$success = $this->panelRepo->save(
					(int) $panel['menu_item_id'],
					(int) $panel['menu_id'],
					$panel
				);

				if ( $success ) {
					++$results['panels'];
				}
			}
		}

		if ( isset( $data['templates'] ) && is_array( $data['templates'] ) ) {
			foreach ( $data['templates'] as $template ) {
				$id = $this->templateRepo->create( $template );

				if ( $id ) {
					++$results['templates'];
				}
			}
		}

		return $results;
	}

	public function importFromJson( string $json ): array {
		$data = json_decode( $json, true );

		if ( ! is_array( $data ) ) {
			return array(
				'settings'  => false,
				'panels'    => 0,
				'templates' => 0,
				'errors'    => array( __( 'Invalid JSON format.', 'imedia-menu' ) ),
			);
		}

		return $this->import( $data );
	}
}
