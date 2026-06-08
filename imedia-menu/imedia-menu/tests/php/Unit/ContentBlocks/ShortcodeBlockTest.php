<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\ShortcodeBlock;
use PHPUnit\Framework\TestCase;

final class ShortcodeBlockTest extends TestCase {

	private ShortcodeBlock $block;

	protected function setUp(): void {
		$this->block = new ShortcodeBlock();
	}

	public function testType(): void {
		$this->assertSame( 'shortcode', $this->block->type() );
	}

	public function testTitle(): void {
		$this->assertNotEmpty( $this->block->title() );
	}

	public function testRenderEmpty(): void {
		$output = $this->block->render( array() );
		$this->assertStringContainsString( 'imm-empty', $output );
	}

	public function testRenderWithShortcode(): void {
		$output = $this->block->render( array( 'shortcode' => '[test]' ) );
		$this->assertStringContainsString( 'imm-block--shortcode', $output );
	}

	public function testDefaultConfig(): void {
		$config = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'shortcode', $config );
		$this->assertSame( '', $config['shortcode'] );
	}
}
