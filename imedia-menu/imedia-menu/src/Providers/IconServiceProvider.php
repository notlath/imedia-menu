<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Icons\IconManager;
use IMedia\Menu\Icons\Providers\DashiconsProvider;
use IMedia\Menu\Icons\Providers\FontAwesomeProvider;
use IMedia\Menu\Icons\Providers\FontAwesome5Provider;
use IMedia\Menu\Icons\Providers\FontAwesome6Provider;
use IMedia\Menu\Icons\Providers\GenericonsProvider;
use IMedia\Menu\Icons\Providers\BootstrapIconsProvider;
use IMedia\Menu\Icons\Providers\CustomSvgProvider;

final class IconServiceProvider implements ServiceProvider {

	private IconManager $manager;

	public function register(): void {
		$this->manager = new IconManager();
	}

	public function boot(): void {
		$settings         = get_option( 'imedia_menu_settings', array() );
		$enabledProviders = $settings['icon_providers'] ?? array( 'dashicons' );

		if ( in_array( 'dashicons', $enabledProviders, true ) ) {
			$this->manager->register( new DashiconsProvider() );
		}

		if ( in_array( 'fontawesome', $enabledProviders, true ) ) {
			$this->manager->register( new FontAwesomeProvider() );
		}

		if ( in_array( 'fontawesome5', $enabledProviders, true ) ) {
			$this->manager->register( new FontAwesome5Provider() );
		}

		if ( in_array( 'fontawesome6', $enabledProviders, true ) ) {
			$this->manager->register( new FontAwesome6Provider() );
		}

		if ( in_array( 'genericons', $enabledProviders, true ) ) {
			$this->manager->register( new GenericonsProvider() );
		}

		if ( in_array( 'bootstrap_icons', $enabledProviders, true ) ) {
			$this->manager->register( new BootstrapIconsProvider() );
		}

		if ( in_array( 'custom_svg', $enabledProviders, true ) ) {
			$this->manager->register( new CustomSvgProvider() );
		}
	}

	public function getManager(): IconManager {
		return $this->manager;
	}
}
