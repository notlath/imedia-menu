<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\HeadingBlock;
use PHPUnit\Framework\TestCase;

final class HeadingBlockTest extends TestCase {

	private HeadingBlock $block;

	protected function setUp(): void {
		$this->block = new HeadingBlock();
	}

	public function testType(): void {
		$this->assertSame( 'heading', $this->block->type() );
	}

	public function testRenderWithText(): void {
		$output = $this->block->render( array( 'text' => 'Hello World' ) );
		$this->assertStringContainsString( 'Hello World', $output );
		$this->assertStringContainsString( '<h', $output );
	}

	public function testRenderDefaultLevel(): void {
		$output = $this->block->render( array( 'text' => 'Test' ) );
		$this->assertStringContainsString( '<h3', $output );
	}

	public function testRenderCustomLevel(): void {
		$output = $this->block->render(
			array(
				'text'  => 'Test',
				'level' => 'h2',
			)
		);
		$this->assertStringContainsString( '<h2', $output );
	}

	public function testRenderEmpty(): void {
		$output = $this->block->render( array() );
		$this->assertStringContainsString( 'imm-block--heading', $output );
	}
}
