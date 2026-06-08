<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\HtmlBlock;
use PHPUnit\Framework\TestCase;

final class HtmlBlockTest extends TestCase {

	private HtmlBlock $block;

	protected function setUp(): void {
		$this->block = new HtmlBlock();
	}

	public function testType(): void {
		$this->assertSame( 'html', $this->block->type() );
	}

	public function testRenderWithContent(): void {
		$output = $this->block->render( array( 'html' => '<div>Custom HTML</div>' ) );
		$this->assertStringContainsString( 'Custom HTML', $output );
	}

	public function testRenderEmpty(): void {
		$output = $this->block->render( array() );
		$this->assertStringContainsString( 'imm-block--html', $output );
	}
}
