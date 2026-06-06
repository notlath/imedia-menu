<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Cache\MenuCache;
use IMedia\Menu\Cache\CacheInvalidator;

final class CacheServiceProvider implements ServiceProvider {

	private MenuCache $cache;
	private CacheInvalidator $invalidator;

	public function register(): void {
		$this->cache       = new MenuCache();
		$this->invalidator = new CacheInvalidator();
	}

	public function boot(): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$enabled  = $settings['enable_caching'] ?? true;

		if ( ! $enabled ) {
			return;
		}

		$this->invalidator->registerHooks();
	}

	public function getCache(): MenuCache {
		return $this->cache;
	}

	public function getInvalidator(): CacheInvalidator {
		return $this->invalidator;
	}
}
