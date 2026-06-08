<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\SearchBlock;
use PHPUnit\Framework\TestCase;

final class SearchBlockTest extends TestCase {

	private SearchBlock $block;

	protected function setUp(): void {
		$this->block = new SearchBlock();
	}

	public function testType(): void {
		$this->assertSame( 'search', $this->block->type() );
	}

	public function testTitle(): void {
		$this->assertNotEmpty( $this->block->title() );
	}

	public function testRenderIsNotEmpty(): void {
		$output = $this->block->render( array() );
		$this->assertNotEmpty( $output );
	}

	public function testRenderContainsForm(): void {
		$output = $this->block->render( array() );
		$this->assertStringContainsString( 'imm-block--search', $output );
	}

	public function testRenderIconOnly(): void {
		$output = $this->block->render( array( 'icon_only' => true ) );
		$this->assertStringContainsString( 'imm-search-toggle', $output );
	}

	public function testDefaultConfig(): void {
		$config = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'placeholder', $config );
		$this->assertArrayHasKey( 'style', $config );
		$this->assertArrayHasKey( 'icon_only', $config );
	}
}
