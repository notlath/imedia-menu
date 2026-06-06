<?php

declare(strict_types=1);

namespace IMedia\Menu\Database;

final class MigrationRunner {

	private const MIGRATIONS_NAMESPACE = 'IMedia\\Menu\\Database\\Migrations\\';
	private const OPTION_KEY           = 'imedia_menu_db_version';

	public function run(): void {
		$currentVersion = get_option( self::OPTION_KEY, '0.0.0' );
		$migrations     = $this->getMigrations();

		foreach ( $migrations as $version => $class ) {
			if ( version_compare( $version, $currentVersion, '>' ) ) {
				$migration = new $class();
				$migration->up();
				update_option( self::OPTION_KEY, $version );
			}
		}
	}

	public function rollback(): void {
		$currentVersion = get_option( self::OPTION_KEY, '0.0.0' );
		$migrations     = $this->getMigrations();

		foreach ( array_reverse( $migrations ) as $version => $class ) {
			if ( version_compare( $version, $currentVersion, '<=' ) ) {
				$migration = new $class();
				$migration->down();
				update_option( self::OPTION_KEY, '0.0.0' );
			}
		}
	}

	private function getMigrations(): array {
		$migrations = array();

		foreach ( glob( IMEDIA_MENU_DIR . '/src/Database/Migrations/Migration_*.php' ) as $file ) {
			$class = self::MIGRATIONS_NAMESPACE . pathinfo( $file, PATHINFO_FILENAME );

			if ( class_exists( $class ) ) {
				$reflection = new \ReflectionClass( $class );
				$attributes = $reflection->getAttributes( Migration::class );

				if ( ! empty( $attributes ) ) {
					$version                = $attributes[0]->newInstance()->version;
					$migrations[ $version ] = $class;
				}
			}
		}

		ksort( $migrations, SORT_STRING );
		return $migrations;
	}
}
