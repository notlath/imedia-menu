<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Database;

use IMedia\Menu\Database\RevisionRepository;
use PHPUnit\Framework\TestCase;

final class RevisionRepositoryTest extends TestCase {

	public function testFindByPanelReturnsEmptyArray(): void {
		$repo = new RevisionRepository();
		$this->assertSame( array(), $repo->findByPanel( 1 ) );
	}

	public function testCreateInsertsNewRevision(): void {
		$repo   = new RevisionRepository();
		$result = $repo->create( 1, 5, array( 'columns' => 3 ), array( 'bg' => '#fff' ), 1 );

		$this->assertNull( $result );
	}

	public function testCreateWithNullStyles(): void {
		$repo   = new RevisionRepository();
		$result = $repo->create( 2, 6, array( 'columns' => 2 ), null, 1 );

		$this->assertNull( $result );
	}

	public function testRestoreReturnsNull(): void {
		$repo = new RevisionRepository();
		$this->assertNull( $repo->restore( 999 ) );
	}

	public function testDeleteByPanelReturnsTrue(): void {
		$repo   = new RevisionRepository();
		$result = $repo->deleteByPanel( 1 );

		$this->assertTrue( $result );
	}
}
