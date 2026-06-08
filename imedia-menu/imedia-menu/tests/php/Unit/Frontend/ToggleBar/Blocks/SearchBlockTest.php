<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar\Blocks;

use IMedia\Menu\Frontend\ToggleBar\Blocks\SearchBlock;
use PHPUnit\Framework\TestCase;

final class SearchBlockTest extends TestCase {

	public function testTypeReturnsSearch(): void {
		$block = new SearchBlock();
		$this->assertSame( 'search', $block->type() );
	}

	public function testLabelReturnsSearch(): void {
		$block = new SearchBlock();
		$this->assertSame( 'Search', $block->label() );
	}

	public function testDefaultSettingsAreValid(): void {
		$block = new SearchBlock();
		$defaults = $block->defaultSettings();

		$this->assertSame( 'Search...', $defaults['placeholder'] );
		$this->assertSame( 'get', $defaults['method'] );
		$this->assertNotEmpty( $defaults['action'] );
	}

	public function testValidateDefaultsForMissingFields(): void {
		$block = new SearchBlock();
		$validated = $block->validate( array() );

		$this->assertSame( 'Search...', $validated['placeholder'] );
		$this->assertSame( 'get', $validated['method'] );
	}

	public function testRenderContainsFormAndInput(): void {
		$block = new SearchBlock();
		$html = $block->render( array(), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( '<form', $html );
		$this->assertStringContainsString( 'role="search"', $html );
		$this->assertStringContainsString( '<input type="search"', $html );
		$this->assertStringContainsString( 'name="s"', $html );
		$this->assertStringContainsString( 'imm-search-icon', $html );
		$this->assertStringContainsString( 'imm-toggle-block--search', $html );
	}

	public function testRenderUsesCustomPlaceholder(): void {
		$block = new SearchBlock();
		$html = $block->render( array( 'placeholder' => 'Find products' ), array( 'block_id' => 'b1' ) );

		$this->assertStringContainsString( 'Find products', $html );
	}

	public function testRequiredAssetsPointToToggleBar(): void {
		$block = new SearchBlock();
		$this->assertSame( 'imm-toggle-bar', $block->requiredStylesheet() );
		$this->assertSame( 'imm-toggle-bar', $block->requiredScript() );
	}
}