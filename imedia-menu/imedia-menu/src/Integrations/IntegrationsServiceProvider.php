<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations;

use IMedia\Menu\Contracts\ServiceProvider;

final class IntegrationsServiceProvider implements ServiceProvider {

	/** @var ServiceProvider[] */
	private array $providers = array();

	public function register(): void {
		$this->providers[] = new WPML\WPMLIntegration();
		$this->providers[] = new Polylang\PolylangIntegration();
		$this->providers[] = new TranslatePress\TranslatePressIntegration();
		$this->providers[] = new Elementor\ElementorIntegration();
		$this->providers[] = new Bricks\BricksIntegration();
		$this->providers[] = new Divi\DiviIntegration();
		$this->providers[] = new Breakdance\BreakdanceIntegration();
	}

	public function boot(): void {
		foreach ( $this->providers as $provider ) {
			$provider->register();
			$provider->boot();
		}
	}
}
