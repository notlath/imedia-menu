<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Database;

use IMedia\Menu\Database\MigrationRunner;
use PHPUnit\Framework\TestCase;

final class MigrationRunnerTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['_imedia_menu_options'] = array();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
		parent::tearDown();
	}

	public function testRunDoesNotThrowWhenNoMigrationsFound(): void {
		$runner = new MigrationRunner();
		$runner->run();
		$this->expectNotToPerformAssertions();
	}

	public function testRollbackDoesNotThrow(): void {
		$runner = new MigrationRunner();
		$runner->rollback();
		$this->expectNotToPerformAssertions();
	}

	public function testRunUpdatesDbVersionOption(): void {
		$GLOBALS['_imedia_menu_options']['imedia_menu_db_version'] = '0.0.0';
		$runner = new MigrationRunner();
		$runner->run();
		$this->assertSame( '0.0.0', get_option( 'imedia_menu_db_version', '0.0.0' ) );
	}
}
