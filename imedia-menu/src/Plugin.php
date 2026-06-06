<?php

declare(strict_types=1);

namespace IMedia\Menu;

use IMedia\Menu\Contracts\ServiceProvider;

final class Plugin {

	private static ?self $instance = null;

	private bool $booted = false;

	/** @var ServiceProvider[] */
	private array $providers = array();

	public static function init(): void {
		self::$instance ??= new self();
		self::$instance->boot();
	}

	public static function instance(): self {
		if ( self::$instance === null ) {
			throw new \RuntimeException( 'Plugin not initialized. Call Plugin::init() first.' );
		}
		return self::$instance;
	}

	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		$this->booted = true;

		$shared = array(
			Providers\CacheServiceProvider::class,
			Providers\VisibilityServiceProvider::class,
			Providers\RestApiServiceProvider::class,
			Providers\IconServiceProvider::class,
			Providers\TemplateServiceProvider::class,
			Providers\MigrationServiceProvider::class,
			Providers\BlockEditorServiceProvider::class,
		);

		if ( is_admin() ) {
			$this->bootProviders(
				array(
					...$shared,
					Providers\SettingsServiceProvider::class,
					Providers\AdminServiceProvider::class,
					Providers\MenuEditorServiceProvider::class,
					Providers\MegaPanelServiceProvider::class,
					Providers\RevisionServiceProvider::class,
				)
			);
		} else {
			$this->bootProviders(
				array(
					...$shared,
					Providers\FrontendServiceProvider::class,
					Providers\MobileServiceProvider::class,
				)
			);
		}

		do_action( 'imedia_menu_loaded' );
	}

	public function bootProviders( array $classes ): void {
		foreach ( $classes as $class ) {
			$provider = new $class();
			$provider->register();
			$provider->boot();
			$this->providers[] = $provider;
		}
	}

	public function getProvider( string $class ): ?ServiceProvider {
		foreach ( $this->providers as $provider ) {
			if ( $provider instanceof $class ) {
				return $provider;
			}
		}
		return null;
	}
}
