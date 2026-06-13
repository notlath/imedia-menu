<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Fonts\FontsManager;

final class FontsServiceProvider implements ServiceProvider {

	private FontsManager $manager;

	public function register(): void {
		$this->manager = new FontsManager();
	}

	public function boot(): void {
		add_action( 'wp_enqueue_scripts', array( $this->manager, 'enqueue' ), 110 );
	}

	public function getManager(): FontsManager {
		return $this->manager;
	}
}
