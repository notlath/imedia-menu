<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Bricks\Elements;

use Bricks\Element;

final class MenuLocation extends Element {

	/** @var string */
	public $category = 'WordPress';
	/** @var string */
	public $name     = 'imedia-menu-location';
	/** @var string */
	public $icon     = 'ti-layout-menu-full';

	public function get_label(): string {
		return esc_html__( 'iMedia Menu Location', 'imedia-menu' );
	}

	public function set_controls(): void {
		$locations = get_registered_nav_menus();
		$options   = array( '' => esc_html__( '— Select —', 'imedia-menu' ) );
		foreach ( $locations as $slug => $name ) {
			$options[ $slug ] = $name;
		}

		$this->controls['location'] = array(
			'tab'     => 'content',
			'label'   => esc_html__( 'Menu Location', 'imedia-menu' ),
			'type'    => 'select',
			'options' => $options,
			'default' => '',
		);
	}

	public function render(): void {
		$settings = $this->settings;
		$location = $settings['location'] ?? '';

		if ( empty( $location ) ) {
			$this->render_element_placeholder( array( 'title' => esc_html__( 'Select a menu location.', 'imedia-menu' ) ) );
			return;
		}

		wp_nav_menu(
			array(
				'theme_location' => $location,
				'echo'           => true,
				'fallback_cb'    => '__return_false',
			)
		);
	}
}
