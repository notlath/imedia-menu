<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Database\MigrationRunner;

final class MigrationServiceProvider implements ServiceProvider {

	private MigrationRunner $runner;

	public function register(): void {
		$this->runner = new MigrationRunner();
	}

	public function boot(): void {
		add_action( 'admin_init', array( $this, 'checkMigrations' ) );
	}

	public function checkMigrations(): void {
		$dbVersion = get_option( 'imedia_menu_db_version', '0.0.0' );

		if ( version_compare( $dbVersion, VERSION, '<' ) ) {
			$this->runner->run();
		}
	}

	public function getRunner(): MigrationRunner {
		return $this->runner;
	}
}
