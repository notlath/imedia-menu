<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Blocks\Navigation\Navigation;
use IMedia\Menu\Contracts\ServiceProvider;

final class BlockEditorServiceProvider implements ServiceProvider {

	public function register(): void {
	}

	public function boot(): void {
		add_action( 'init', array( Navigation::class, 'register' ) );
	}
}
