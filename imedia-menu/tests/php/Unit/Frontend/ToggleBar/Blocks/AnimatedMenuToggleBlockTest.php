<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\AnimatedMenuToggleBlock;
use PHPUnit\Framework\TestCase;

final class AnimatedMenuToggleBlockTest extends TestCase {

	public function testTypeReturnsMenuToggleAnimated(): void {
		$block = new AnimatedMenuToggleBlock();
		$this->assertSame( 'menu_toggle_animated', $block->type() );
	}

	public function testLabelReturnsAnimatedMenuToggle(): void {
		$block = new AnimatedMenuToggleBlock();
		$this->assertSame( 'Animated Menu Toggle', $block->label() );
	}

	public function testDefaultSettingsAreValid(): void {
		$block = new AnimatedMenuToggleBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( 'arrow', $defaults['animation'] );
		$this->assertFalse( $defaults['icon_only'] );
	}

	public function testValidateDefaultsToArrowForInvalidAnimation(): void {
		$block = new AnimatedMenuToggleBlock();
		$validated = $block->validate( array( 'animation' => 'fade' ) );

		$this->assertSame( 'arrow', $validated['animation'] );
	}

	public function testValidateAcceptsArrowAndSlider(): void {
		$block = new AnimatedMenuToggleBlock();

		$this->assertSame( 'arrow', $block->validate( array( 'animation' => 'arrow' ) )['animation'] );
		$this->assertSame( 'slider', $block->validate( array( 'animation' => 'slider' ) )['animation'] );
	}

	public function testRenderArrowAnimationHasBars(): void {
		$block = new AnimatedMenuToggleBlock();
		$html = $block->render( array( 'animation' => 'arrow' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-anim--arrow', $html );
		$this->assertStringContainsString( 'imm-toggle-anim-bars', $html );
		$this->assertStringContainsString( 'data-animation="arrow"', $html );
	}

	public function testRenderSliderAnimationHasSlider(): void {
		$block = new AnimatedMenuToggleBlock();
		$html = $block->render( array( 'animation' => 'slider' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-anim--slider', $html );
		$this->assertStringContainsString( 'imm-toggle-anim-slider', $html );
		$this->assertStringContainsString( 'data-animation="slider"', $html );
	}

	public function testRequiredAssetsPointToToggleBar(): void {
		$block = new AnimatedMenuToggleBlock();
		$this->assertSame( 'imm-toggle-bar', $block->requiredStylesheet() );
		$this->assertSame( 'imm-toggle-bar', $block->requiredScript() );
	}
}