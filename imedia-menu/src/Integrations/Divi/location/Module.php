<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Divi\location;

class Module extends \ET_Builder_Module {

	/** @var array */
	public $module_credits = array(
		'module_uri' => 'https://inventivemedia.com',
		'author'     => 'Inventive Media',
		'author_uri' => 'https://inventivemedia.com',
	);

	public function init(): void {
		$this->name       = esc_html__( 'iMedia Menu Location', 'imedia-menu' );
		$this->slug       = 'imm_media_menu_location';
		$this->vb_support = 'on';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Menu Settings', 'imedia-menu' ),
				),
			),
		);
	}

	public function get_fields(): array {
		$locations = get_registered_nav_menus();
		$options   = array( '' => esc_html__( '— Select —', 'imedia-menu' ) );
		foreach ( $locations as $slug => $name ) {
			$options[ $slug ] = $name;
		}

		return array(
			'location' => array(
				'label'           => esc_html__( 'Menu Location', 'imedia-menu' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'options'         => $options,
				'default'         => key( $options ) !== false ? key( $options ) : '',
			),
		);
	}

	public function render( array $attrs, string $content = '', string $render_slug = '' ): string {
		$location = $this->props['location'] ?? '';

		if ( empty( $location ) ) {
			return '';
		}

		return wp_nav_menu(
			array(
				'theme_location' => $location,
				'echo'           => false,
				'fallback_cb'    => '__return_false',
			)
		);
	}
}
