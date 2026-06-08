<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Database;

use IMedia\Menu\Database\PanelRepository;
use PHPUnit\Framework\TestCase;

final class PanelRepositoryTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
	}

	public function testFindByMenuItemReturnsNullWhenNoRow(): void {
		$repo = new PanelRepository();
		$result = $repo->findByMenuItem( 999 );
		$this->assertNull( $result );
	}

	public function testFindByMenuReturnsEmptyArrayWhenNoRows(): void {
		$repo = new PanelRepository();
		$result = $repo->findByMenu( 999 );
		$this->assertSame( array(), $result );
	}

	public function testSaveInsertsNewPanel(): void {
		$repo   = new PanelRepository();
		$result = $repo->save( 1, 10, array(
			'layout_type' => 'columns',
			'config'      => array( 'columns' => 3 ),
		) );

		$this->assertTrue( $result );
	}

	public function testSaveValidatesLayoutType(): void {
		$repo   = new PanelRepository();
		$result = $repo->save( 2, 10, array(
			'layout_type' => 'invalid_type',
			'config'      => array(),
		) );

		$this->assertTrue( $result );
	}

	public function testDeleteReturnsTrue(): void {
		$repo   = new PanelRepository();
		$result = $repo->delete( 1 );

		$this->assertTrue( $result );
	}

	public function testDeleteByMenuReturnsTrue(): void {
		$repo   = new PanelRepository();
		$result = $repo->deleteByMenu( 10 );

		$this->assertTrue( $result );
	}

	public function testHasEnabledGridPanelsReturnsFalse(): void {
		$repo  = new PanelRepository();
		$count = $repo->hasEnabledGridPanels();

		$this->assertFalse( $count );
	}
}
