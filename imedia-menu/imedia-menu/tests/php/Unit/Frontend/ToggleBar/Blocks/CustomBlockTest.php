<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\CustomBlock;
use PHPUnit\Framework\TestCase;

final class CustomBlockTest extends TestCase {

	public function testTypeReturnsCustom(): void {
		$block = new CustomBlock();
		$this->assertSame( 'custom', $block->type() );
	}

	public function testLabelReturnsCustomShortcode(): void {
		$block = new CustomBlock();
		$this->assertSame( 'Custom / Shortcode', $block->label() );
	}

	public function testDefaultSettingsHaveEmptyShortcode(): void {
		$block = new CustomBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( '', $defaults['shortcode'] );
	}

	public function testValidateFillsEmptyShortcode(): void {
		$block = new CustomBlock();
		$validated = $block->validate( array() );

		$this->assertSame( '', $validated['shortcode'] );
	}

	public function testRenderWithEmptyShortcodeProducesEmptyDiv(): void {
		$block = new CustomBlock();
		$html = $block->render( array(), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-block--custom', $html );
		$this->assertStringContainsString( 'data-block-id="b1"', $html );
	}

	public function testRenderExecutesShortcode(): void {
		$block = new CustomBlock();
		$html = $block->render( array( 'shortcode' => '[gallery]' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-block--custom', $html );
	}
}