<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Database;

use IMedia\Menu\Database\Schema;
use PHPUnit\Framework\TestCase;

final class SchemaTest extends TestCase {

	public function testConstantsAreDefined(): void {
		$this->assertSame( 'imedia_menu_panels', Schema::PANELS_TABLE );
		$this->assertSame( 'imedia_menu_templates', Schema::TEMPLATES_TABLE );
		$this->assertSame( 'imedia_menu_revisions', Schema::REVISIONS_TABLE );
	}

	public function testCreateDoesNotThrow(): void {
		$schema = new Schema();
		$schema->create();
		$this->expectNotToPerformAssertions();
	}

	public function testValidateRequirementsDoesNotThrow(): void {
		$schema = new Schema();
		$schema->validateRequirements();
		$this->expectNotToPerformAssertions();
	}

	public function testDropDoesNotThrow(): void {
		$schema = new Schema();
		$schema->drop();
		$this->expectNotToPerformAssertions();
	}
}
