<?php

declare(strict_types=1);

namespace IMedia\Menu\Export;

use IMedia\Menu\Database\PanelRepository;
use IMedia\Menu\Database\TemplateRepository;

final class Exporter {

	private PanelRepository $panelRepo;
	private TemplateRepository $templateRepo;

	public function __construct() {
		$this->panelRepo    = new PanelRepository();
		$this->templateRepo = new TemplateRepository();
	}

	public function export(): array {
		global $wpdb;

		$data = array(
			'version'   => IMEDIA_MENU_VERSION,
			'exported'  => current_time( 'mysql' ),
			'settings'  => get_option( 'imedia_menu_settings', array() ),
			'panels'    => $this->exportPanels(),
			'templates' => $this->templateRepo->findAll(),
			'menus'     => $this->exportMenus(),
		);

		return $data;
	}

	public function exportJson(): string {
		$data = $this->export();

		return wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	private function exportPanels(): array {
		global $wpdb;

		$table = $wpdb->prefix . \IMedia\Menu\Database\Schema::PANELS_TABLE;

		$rows = $wpdb->get_results(
			"SELECT * FROM {$table}",
			ARRAY_A
		);

		foreach ( $rows as &$row ) {
			$row['config'] = json_decode( $row['config'], true );
			$row['styles'] = $row['styles'] ? json_decode( $row['styles'], true ) : null;
			unset( $row['id'] );
			unset( $row['created_at'] );
			unset( $row['updated_at'] );
		}

		return $rows;
	}

	private function exportMenus(): array {
		$menus  = wp_get_nav_menus();
		$result = array();

		foreach ( $menus as $menu ) {
			$items    = wp_get_nav_menu_items( $menu->term_id );
			$itemData = array();

			if ( $items ) {
				foreach ( $items as $item ) {
					$meta = array();

					$metaKeys = array(
						'_imedia_menu_mega_enabled',
						'_imedia_menu_icon',
						'_imedia_menu_icon_position',
						'_imedia_menu_badge_text',
						'_imedia_menu_badge_color',
						'_imedia_menu_badge_text_color',
						'_imedia_menu_badge_position',
						'_imedia_menu_description',
						'_imedia_menu_disable_link',
						'_imedia_menu_visibility',
					);

					foreach ( $metaKeys as $key ) {
						$meta[ $key ] = get_post_meta( $item->ID, $key, true );
					}

					$itemData[] = array(
						'id'        => $item->ID,
						'title'     => $item->title,
						'url'       => $item->url,
						'target'    => $item->target,
						'classes'   => $item->classes,
						'parent'    => $item->menu_item_parent,
						'order'     => $item->menu_order,
						'type'      => $item->type,
						'object'    => $item->object,
						'object_id' => $item->object_id,
						'meta'      => $meta,
					);
				}
			}

			$result[] = array(
				'id'        => $menu->term_id,
				'name'      => $menu->name,
				'slug'      => $menu->slug,
				'locations' => get_nav_menu_locations(),
				'items'     => $itemData,
			);
		}

		return $result;
	}
}
