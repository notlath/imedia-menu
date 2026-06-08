<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\SpacerBlock;
use PHPUnit\Framework\TestCase;

final class SpacerBlockTest extends TestCase {

	public function testTypeReturnsSpacer(): void {
		$block = new SpacerBlock();
		$this->assertSame( 'spacer', $block->type() );
	}

	public function testLabelReturnsSpacer(): void {
		$block = new SpacerBlock();
		$this->assertSame( 'Spacer', $block->label() );
	}

	public function testDefaultSettingsHaveWidth(): void {
		$block = new SpacerBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( '20px', $defaults['width'] );
	}

	public function testValidateFillsDefaultWidth(): void {
		$block = new SpacerBlock();
		$validated = $block->validate( array() );

		$this->assertSame( '20px', $validated['width'] );
	}

	public function testRenderOutputsSpacerDivWithWidth(): void {
		$block = new SpacerBlock();
		$html = $block->render( array( 'width' => '30px' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<div', $html );
		$this->assertStringContainsString( 'imm-toggle-block--spacer', $html );
		$this->assertStringContainsString( 'width:30px', $html );
		$this->assertStringContainsString( 'aria-hidden="true"', $html );
	}

	public function testRenderDefaultsTo20pxWidth(): void {
		$block = new SpacerBlock();
		$html = $block->render( array(), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'width:20px', $html );
	}
}