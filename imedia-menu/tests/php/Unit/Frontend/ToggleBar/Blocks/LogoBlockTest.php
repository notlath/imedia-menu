<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\LogoBlock;
use PHPUnit\Framework\TestCase;

final class LogoBlockTest extends TestCase {

	public function testTypeReturnsLogo(): void {
		$block = new LogoBlock();
		$this->assertSame( 'logo', $block->type() );
	}

	public function testLabelReturnsLogo(): void {
		$block = new LogoBlock();
		$this->assertSame( 'Logo', $block->label() );
	}

	public function testDefaultSettingsAreValid(): void {
		$block = new LogoBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( 0, $defaults['logo_id'] );
		$this->assertSame( '_self', $defaults['target'] );
		$this->assertNotEmpty( $defaults['url'] );
	}

	public function testValidateConvertsLogoIdToInt(): void {
		$block = new LogoBlock();
		$validated = $block->validate( array( 'logo_id' => '42' ) );

		$this->assertSame( 42, $validated['logo_id'] );
	}

	public function testValidateRejectsInvalidTarget(): void {
		$block = new LogoBlock();
		$validated = $block->validate( array( 'target' => '_parent' ) );

		$this->assertSame( '_self', $validated['target'] );
	}

	public function testRenderShowsPlaceholderWhenNoLogo(): void {
		$block = new LogoBlock();
		$html = $block->render( array( 'logo_id' => 0 ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-logo-placeholder', $html );
		$this->assertStringContainsString( 'imm-toggle-block--logo', $html );
	}

	public function testRenderWrapsLinkInAnchorWhenUrlSet(): void {
		$block = new LogoBlock();
		$html = $block->render( array( 'url' => 'https://example.com/' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<a href="https://example.com/"', $html );
	}

	public function testRenderAddsNoopenerForBlankTarget(): void {
		$block = new LogoBlock();
		$html = $block->render( array( 'url' => 'https://example.com/', 'target' => '_blank' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'rel="noopener noreferrer"', $html );
	}
}