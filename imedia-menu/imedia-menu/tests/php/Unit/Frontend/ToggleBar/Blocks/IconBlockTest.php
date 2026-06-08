<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\IconBlock;
use PHPUnit\Framework\TestCase;

final class IconBlockTest extends TestCase {

	public function testTypeReturnsIcon(): void {
		$block = new IconBlock();
		$this->assertSame( 'icon', $block->type() );
	}

	public function testLabelReturnsIcon(): void {
		$block = new IconBlock();
		$this->assertSame( 'Icon', $block->label() );
	}

	public function testDefaultSettingsAreValid(): void {
		$block = new IconBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( '', $defaults['icon'] );
		$this->assertSame( '', $defaults['url'] );
		$this->assertSame( '_self', $defaults['target'] );
	}

	public function testValidateFillsDefaultsForMissing(): void {
		$block = new IconBlock();
		$validated = $block->validate( array() );

		$this->assertSame( '', $validated['icon'] );
		$this->assertSame( '_self', $validated['target'] );
	}

	public function testRenderContainsIconWrapper(): void {
		$block = new IconBlock();
		$html = $block->render( array( 'icon' => 'dashicons-search' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-block--icon', $html );
		$this->assertStringContainsString( 'dashicons dashicons-search', $html );
	}

	public function testRenderWrapsInLinkWhenUrlSet(): void {
		$block = new IconBlock();
		$html = $block->render( array( 'icon' => 'dashicons-cart', 'url' => 'https://shop.example.com/' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<a href="https://shop.example.com/"', $html );
		$this->assertStringContainsString( 'aria-label=""', $html );
	}

	public function testRenderUsesAriaLabelWhenProvided(): void {
		$block = new IconBlock();
		$html = $block->render( array( 'icon' => 'dashicons-cart', 'url' => '/cart', 'aria_label' => 'Shopping cart' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'aria-label="Shopping cart"', $html );
	}

	public function testRenderRendersCustomHtmlIcon(): void {
		$block = new IconBlock();
		$html = $block->render( array( 'icon' => '<span class="my-icon"></span>' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-custom-icon', $html );
		$this->assertStringContainsString( 'my-icon', $html );
	}
}