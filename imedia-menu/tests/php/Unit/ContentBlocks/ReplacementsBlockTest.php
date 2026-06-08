<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\ReplacementsBlock;
use PHPUnit\Framework\TestCase;

final class ReplacementsBlockTest extends TestCase {

	private ReplacementsBlock $block;

	protected function setUp(): void {
		$this->block = new ReplacementsBlock();
	}

	public function testType(): void {
		$this->assertSame( 'replacements', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'template', $cfg );
		$this->assertArrayHasKey( 'parse_shortcodes', $cfg );
		$this->assertArrayHasKey( 'allowed_html', $cfg );
		$this->assertSame( '', $cfg['template'] );
		$this->assertFalse( $cfg['parse_shortcodes'] );
	}

	public function testRenderEmptyTemplate(): void {
		$output = $this->block->render( array( 'template' => '' ) );
		$this->assertStringContainsString( 'imm-block--replacements', $output );
		$this->assertStringContainsString( 'empty', $output );
	}

	public function testRenderSiteTitleToken(): void {
		$output = $this->block->render( array( 'template' => 'Hello {site_title}' ) );
		$this->assertStringContainsString( 'Test Site', $output );
	}

	public function testRenderUnknownTokenLeavesLiteral(): void {
		$output = $this->block->render( array( 'template' => 'Hello {unknown_token}' ) );
		$this->assertStringContainsString( '{unknown_token}', $output );
	}

	public function testRenderCartTokensEmptyWhenWcInactive(): void {
		$output = $this->block->render( array( 'template' => 'Cart: {cart_count} | Total: {cart_total}' ) );
		$this->assertStringContainsString( 'imm-block--replacements', $output );
		$this->assertStringNotContainsString( '{cart_count}', $output );
	}

	public function testRenderStripsDangerousHtml(): void {
		$output = $this->block->render( array( 'template' => '<script>alert(1)</script>Hello' ) );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function testFilterCanExtendTokenMap(): void {
		$filter = function ( $map ) {
			$map['{custom}'] = 'CUSTOM_VAL';
			return $map;
		};
		add_filter( 'imedia_menu_replacements_token_map', $filter );
		$output = $this->block->render( array( 'template' => 'Value: {custom}' ) );
		remove_filter( 'imedia_menu_replacements_token_map', $filter );
		$this->assertStringContainsString( 'CUSTOM_VAL', $output );
	}
}
