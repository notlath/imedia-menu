<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\HtmlBlock;
use PHPUnit\Framework\TestCase;

final class HtmlBlockTest extends TestCase {

	public function testTypeReturnsHtml(): void {
		$block = new HtmlBlock();
		$this->assertSame( 'html', $block->type() );
	}

	public function testLabelReturnsCustomHtml(): void {
		$block = new HtmlBlock();
		$this->assertSame( 'Custom HTML', $block->label() );
	}

	public function testDefaultSettingsHaveEmptyContent(): void {
		$block = new HtmlBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( '', $defaults['content'] );
	}

	public function testValidateFillsEmptyContent(): void {
		$block = new HtmlBlock();
		$validated = $block->validate( array() );

		$this->assertSame( '', $validated['content'] );
	}

	public function testRenderContainsBlockWrapper(): void {
		$block = new HtmlBlock();
		$html = $block->render( array( 'content' => '<p>Hello</p>' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<div', $html );
		$this->assertStringContainsString( 'imm-toggle-block--html', $html );
		$this->assertStringContainsString( 'data-block-id="b1"', $html );
		$this->assertStringContainsString( '<p>Hello</p>', $html );
	}

	public function testRenderSanitizesDangerousTags(): void {
		$block = new HtmlBlock();
		$html = $block->render( array( 'content' => '<script>alert("xss")</script><p>Safe</p>' ), array( 'block_id' => 'b1' ) );

		$this->assertStringNotContainsString( '<script', $html );
		$this->assertStringContainsString( '<p>Safe</p>', $html );
	}
}