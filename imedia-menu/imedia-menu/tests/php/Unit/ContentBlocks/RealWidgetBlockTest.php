<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\RealWidgetBlock;
use PHPUnit\Framework\TestCase;

final class RealWidgetBlockTest extends TestCase {

	private RealWidgetBlock $block;

	protected function setUp(): void {
		$this->block = new RealWidgetBlock();
	}

	public function testType(): void {
		$this->assertSame( 'real_widget', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'widget_class', $cfg );
		$this->assertArrayHasKey( 'instance', $cfg );
		$this->assertArrayHasKey( 'title', $cfg );
		$this->assertArrayHasKey( 'before_widget', $cfg );
		$this->assertArrayHasKey( 'after_widget', $cfg );
		$this->assertArrayHasKey( 'before_title', $cfg );
		$this->assertArrayHasKey( 'after_title', $cfg );
		$this->assertSame( '', $cfg['widget_class'] );
		$this->assertIsArray( $cfg['instance'] );
	}

	public function testRenderMissingWidgetClass(): void {
		$output = $this->block->render( array( 'widget_class' => '' ) );
		$this->assertStringContainsString( 'imm-block--real-widget', $output );
		$this->assertStringContainsString( 'unavailable', $output );
		$this->assertStringContainsString( '(no widget selected)', $output );
	}

	public function testRenderUnknownClassFallsBack(): void {
		$output = $this->block->render( array( 'widget_class' => 'Nonexistent_Widget_Class' ) );
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
		$this->assertStringContainsString( 'Nonexistent_Widget_Class', $output );
	}

	public function testRenderNonWidgetClassFallsBack(): void {
		$output = $this->block->render( array( 'widget_class' => \stdClass::class ) );
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
	}

	public function testRenderEscapesWidgetClassInDataAttr(): void {
		$output = $this->block->render( array( 'widget_class' => '"><script>' ) );
		$this->assertStringNotContainsString( '<script>', $output );
	}
}
