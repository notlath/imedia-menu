<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class SettingsRegistry {

	/** @var array<string, SettingsTab> */
	private array $tabs = array();

	public function __construct() {
		$this->registerDefaults();
	}

	public function register( SettingsTab $tab ): void {
		$this->tabs[ $tab->id() ] = $tab;
	}

	public function get( string $id ): ?SettingsTab {
		return $this->tabs[ $id ] ?? null;
	}

	/** @return array<string, SettingsTab> */
	public function getAll(): array {
		return $this->tabs;
	}

	public function renderTab( string $id, array $settings ): void {
		$tab = $this->get( $id );

		if ( $tab === null ) {
			echo '<p>' . esc_html__( 'Settings tab not found.', 'imedia-menu' ) . '</p>';
			return;
		}

		$tab->render( $settings );
	}

	public function validateAll( array $input ): array {
		$validated = array();

		foreach ( $this->tabs as $tab ) {
			$validated = array_merge( $validated, $tab->validate( $input ) );
		}

		return $validated;
	}

	public function sanitizeAll( array $input ): array {
		$sanitized = array();

		foreach ( $this->tabs as $tab ) {
			$sanitized = array_merge( $sanitized, $tab->sanitize( $input ) );
		}

		return $sanitized;
	}

	private function registerDefaults(): void {
		$defaults = array(
			new Tabs\GeneralTab(),
			new Tabs\DesignTab(),
			new Tabs\MobileTab(),
			new Tabs\PerformanceTab(),
			new Tabs\AdvancedTab(),
		);

		foreach ( $defaults as $tab ) {
			$this->register( $tab );
		}
	}
}
