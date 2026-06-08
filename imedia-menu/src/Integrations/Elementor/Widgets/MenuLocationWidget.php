<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

final class MenuLocationWidget extends Widget_Base {

	public function get_name(): string {
		return 'imedia_menu_location';
	}

	public function get_title(): string {
		return __( 'iMedia Menu', 'imedia-menu' );
	}

	public function get_icon(): string {
		return 'eicon-nav-menu';
	}

	public function get_categories(): array {
		return array( 'general' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Menu Location', 'imedia-menu' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$locations = get_registered_nav_menus();
		$options   = array( '' => __( '— Select Location —', 'imedia-menu' ) );
		$enabled   = array();

		$assigned = get_nav_menu_locations();
		foreach ( $locations as $slug => $name ) {
			$options[ $slug ] = $name;
			if ( ! empty( $assigned[ $slug ] ) ) {
				$enabled[ $slug ] = $name;
			}
		}

		$grouped = array(
			__( 'Active Locations', 'imedia-menu' )   => $enabled,
			__( 'Inactive Locations', 'imedia-menu' ) => array_diff_key( $options, $enabled ),
		);

		$this->add_control(
			'location',
			array(
				'label'   => __( 'Location', 'imedia-menu' ),
				'type'    => Controls_Manager::SELECT,
				'groups'  => $this->buildGroupedOptions( $grouped ),
				'default' => '',
			)
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$location = $settings['location'] ?? '';

		if ( empty( $location ) ) {
			return;
		}

		$args = array(
			'theme_location' => $location,
			'echo'           => true,
			'fallback_cb'    => '__return_false',
		);

		wp_nav_menu( $args );
	}

	private function buildGroupedOptions( array $groups ): array {
		$result = array();
		foreach ( $groups as $label => $options ) {
			if ( empty( $options ) ) {
				continue;
			}
			$group = array(
				'label'   => $label,
				'options' => array(),
			);
			foreach ( $options as $value => $text ) {
				$group['options'][ $value ] = $text;
			}
			$result[] = $group;
		}
		return $result;
	}
}
