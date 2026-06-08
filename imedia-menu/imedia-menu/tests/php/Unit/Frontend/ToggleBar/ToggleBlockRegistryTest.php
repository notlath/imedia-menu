<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\ToggleBlockRegistry;
use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;
use PHPUnit\Framework\TestCase;

final class ToggleBlockRegistryTest extends TestCase {

	private ToggleBlockRegistry $registry;

	protected function setUp(): void {
		$this->registry = new ToggleBlockRegistry();
	}

	public function testRegisterAndGet(): void {
		$block = $this->createMock( ToggleBlock::class );
		$block->method( 'type' )->willReturn( 'test_block' );

		$this->registry->register( $block );
		$result = $this->registry->get( 'test_block' );

		$this->assertSame( $block, $result );
	}

	public function testGetReturnsNullForUnknownType(): void {
		$result = $this->registry->get( 'unknown' );
		$this->assertNull( $result );
	}

	public function testHasReturnsTrueForRegisteredType(): void {
		$block = $this->createMock( ToggleBlock::class );
		$block->method( 'type' )->willReturn( 'test_block' );

		$this->registry->register( $block );
		$this->assertTrue( $this->registry->has( 'test_block' ) );
		$this->assertFalse( $this->registry->has( 'unknown' ) );
	}

	public function testAllReturnsAllRegisteredBlocks(): void {
		$block1 = $this->createMock( ToggleBlock::class );
		$block1->method( 'type' )->willReturn( 'block1' );
		$block2 = $this->createMock( ToggleBlock::class );
		$block2->method( 'type' )->willReturn( 'block2' );

		$this->registry->register( $block1 );
		$this->registry->register( $block2 );

		$all = $this->registry->all();
		$this->assertCount( 2, $all );
		$this->assertSame( $block1, $all['block1'] );
		$this->assertSame( $block2, $all['block2'] );
	}

	public function testGetTypesReturnsTypeKeys(): void {
		$block1 = $this->createMock( ToggleBlock::class );
		$block1->method( 'type' )->willReturn( 'block1' );
		$block2 = $this->createMock( ToggleBlock::class );
		$block2->method( 'type' )->willReturn( 'block2' );

		$this->registry->register( $block1 );
		$this->registry->register( $block2 );

		$types = $this->registry->getTypes();
		$this->assertSame( array( 'block1', 'block2' ), $types );
	}

	public function testGetLabelsReturnsTypeLabelMap(): void {
		$block1 = $this->createMock( ToggleBlock::class );
		$block1->method( 'type' )->willReturn( 'block1' );
		$block1->method( 'label' )->willReturn( 'Block One' );
		$block2 = $this->createMock( ToggleBlock::class );
		$block2->method( 'type' )->willReturn( 'block2' );
		$block2->method( 'label' )->willReturn( 'Block Two' );

		$this->registry->register( $block1 );
		$this->registry->register( $block2 );

		$labels = $this->registry->getLabels();
		$this->assertSame( array( 'block1' => 'Block One', 'block2' => 'Block Two' ), $labels );
	}
}