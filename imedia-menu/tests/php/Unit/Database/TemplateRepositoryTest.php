<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Database;

use IMedia\Menu\Database\TemplateRepository;
use PHPUnit\Framework\TestCase;

final class TemplateRepositoryTest extends TestCase {

	public function testFindAllReturnsEmptyArray(): void {
		$repo = new TemplateRepository();
		$this->assertSame( array(), $repo->findAll() );
	}

	public function testFindByIdReturnsNull(): void {
		$repo = new TemplateRepository();
		$this->assertNull( $repo->findById( 999 ) );
	}

	public function testCreateInsertNewTemplate(): void {
		$repo   = new TemplateRepository();
		$result = $repo->create( array(
			'name'        => 'Test Template',
			'description' => 'A test template',
			'config'      => array( 'columns' => 2 ),
			'styles'      => array( 'bg' => '#fff' ),
		) );

		$this->assertNull( $result );
	}

	public function testUpdateExistingTemplate(): void {
		$repo   = new TemplateRepository();
		$result = $repo->update( 1, array(
			'name'   => 'Updated Name',
			'config' => array( 'columns' => 4 ),
		) );

		$this->assertTrue( $result );
	}

	public function testUpdateWithNoFieldsReturnsFalse(): void {
		$repo   = new TemplateRepository();
		$result = $repo->update( 1, array() );

		$this->assertFalse( $result );
	}

	public function testDeleteReturnsTrue(): void {
		$repo   = new TemplateRepository();
		$result = $repo->delete( 1 );

		$this->assertTrue( $result );
	}
}
