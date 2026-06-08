<?php

declare(strict_types=1);

namespace IMedia\Menu\Database\Migrations;

use IMedia\Menu\Database\Schema;

#[Migration( version: '1.0.0', description: 'Initial database schema' )]
final class Migration_100 {

	private Schema $schema;

	public function __construct() {
		$this->schema = new Schema();
	}

	public function up(): void {
		$this->schema->create();
	}

	public function down(): void {
		$this->schema->drop();
	}
}
