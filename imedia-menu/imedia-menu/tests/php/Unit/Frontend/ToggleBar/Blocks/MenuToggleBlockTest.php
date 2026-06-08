<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\MenuToggleBlock;
use PHPUnit\Framework\TestCase;

final class MenuToggleBlockTest extends TestCase {

	public function testTypeReturnsMenuToggle(): void {
		$block = new MenuToggleBlock();
		$this->assertSame( 'menu_toggle', $block->type() );
	}

	public function testLabelReturnsMenuToggle(): void {
		$block = new MenuToggleBlock();
		$this->assertSame( 'Menu Toggle', $block->label() );
	}

	public function testDefaultSettingsAreValid(): void {
		$block = new MenuToggleBlock();
		$defaults = $block->defaultSettings();

		$this->assertArrayHasKey( 'label', $defaults );
		$this->assertArrayHasKey( 'icon_only', $defaults );
		$this->assertArrayHasKey( 'aria_label', $defaults );
		$this->assertFalse( $defaults['icon_only'] );
	}

	public function testValidateFillsDefaultsForMissing(): void {
		$block = new MenuToggleBlock();
		$validated = $block->validate( array() );

		$this->assertSame( 'Menu', $validated['label'] );
		$this->assertFalse( $validated['icon_only'] );
	}

	public function testRenderOutputsButton(): void {
		$block = new MenuToggleBlock();
		$html = $block->render( array(), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<button', $html );
		$this->assertStringContainsString( 'imm-toggle-block--menu-toggle', $html );
		$this->assertStringContainsString( 'data-block-id="b1"', $html );
		$this->assertStringContainsString( 'aria-expanded="false"', $html );
		$this->assertStringContainsString( 'imm-hamburger', $html );
	}

	public function testRenderHidesLabelWhenIconOnly(): void {
		$block = new MenuToggleBlock();
		$html = $block->render( array( 'icon_only' => true ), array( 'block_id' => 'b1' ) );

		$this->assertStringNotContainsString( 'imm-toggle-label', $html );
	}

	public function testRenderShowsLabelWhenNotIconOnly(): void {
		$block = new MenuToggleBlock();
		$html = $block->render( array( 'label' => 'Open' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'imm-toggle-label', $html );
		$this->assertStringContainsString( 'Open', $html );
	}

	public function testRequiredStylesheetReturnsNull(): void {
		$block = new MenuToggleBlock();
		$this->assertNull( $block->requiredStylesheet() );
	}
}