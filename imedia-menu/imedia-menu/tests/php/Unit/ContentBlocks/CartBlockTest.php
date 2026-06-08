<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\CartBlock;
use PHPUnit\Framework\TestCase;

final class CartBlockTest extends TestCase {

	private CartBlock $block;

	protected function setUp(): void {
		$this->block = new CartBlock();
	}

	public function testType(): void {
		$this->assertSame( 'cart', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'display', $cfg );
		$this->assertArrayHasKey( 'show_count', $cfg );
		$this->assertArrayHasKey( 'show_total', $cfg );
		$this->assertArrayHasKey( 'show_thumbnails', $cfg );
		$this->assertArrayHasKey( 'empty_text', $cfg );
		$this->assertArrayHasKey( 'cart_url', $cfg );
		$this->assertArrayHasKey( 'hide_when_empty', $cfg );
		$this->assertArrayHasKey( 'icon', $cfg );
		$this->assertSame( 'icon', $cfg['display'] );
	}

	public function testRenderWhenWcInactiveShowsUnavailable(): void {
		$GLOBALS['__wc_active'] = false;
		$output                 = $this->block->render(
			array(
				'empty_text' => 'No cart',
			)
		);
		$this->assertStringContainsString( 'imm-block--cart', $output );
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
		unset( $GLOBALS['__wc_active'] );
	}

	public function testRenderWhenWcInactiveAndHideWhenEmptyReturnsEmpty(): void {
		$GLOBALS['__wc_active'] = false;
		$output                 = $this->block->render( array( 'hide_when_empty' => true ) );
		$this->assertSame( '', $output );
		unset( $GLOBALS['__wc_active'] );
	}

	public function testRenderIconModeWithEmptyCart(): void {
		$output = $this->block->render(
			array(
				'display'    => 'icon',
				'empty_text' => 'Empty',
			)
		);
		$this->assertStringContainsString( 'imm-block--cart--icon', $output );
		$this->assertStringContainsString( 'data-count="0"', $output );
		$this->assertStringContainsString( 'Empty', $output );
	}

	public function testRenderRendersCustomIcon(): void {
		$output = $this->block->render(
			array(
				'display' => 'icon',
				'icon'    => 'dashicons-basket',
			)
		);
		$this->assertStringContainsString( 'dashicons-basket', $output );
	}
}
