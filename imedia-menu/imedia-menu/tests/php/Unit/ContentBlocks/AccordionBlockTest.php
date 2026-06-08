<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\AccordionBlock;
use IMedia\Menu\ContentBlocks\Registry;
use PHPUnit\Framework\TestCase;

final class AccordionBlockTest extends TestCase {

	private AccordionBlock $block;
	private Registry $registry;

	protected function setUp(): void {
		$this->block    = new AccordionBlock();
		$this->registry = new Registry();
		$this->block->setRegistry( $this->registry );
	}

	public function testType(): void {
		$this->assertSame( 'accordion', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'items', $cfg );
		$this->assertArrayHasKey( 'multi_open', $cfg );
		$this->assertArrayHasKey( 'allow_toggle_all', $cfg );
		$this->assertIsArray( $cfg['items'] );
		$this->assertFalse( $cfg['multi_open'] );
	}

	public function testRenderEmptyItems(): void {
		$output = $this->block->render( array( 'items' => array() ) );
		$this->assertStringContainsString( 'imm-block--accordion', $output );
		$this->assertStringContainsString( 'No accordion items', $output );
	}

	public function testRenderSingleItemUsesDetailsElement(): void {
		$output = $this->block->render(
			array(
				'items' => array(
					array(
						'id'     => 'a1',
						'label'  => 'First',
						'blocks' => array(),
					),
				),
			)
		);
		$this->assertStringContainsString( '<details', $output );
		$this->assertStringContainsString( 'First', $output );
		$this->assertStringContainsString( 'id="a1"', $output );
	}

	public function testRenderInitiallyOpen(): void {
		$output = $this->block->render(
			array(
				'items' => array(
					array(
						'id'             => 'a1',
						'label'          => 'Open',
						'initially_open' => true,
						'blocks'         => array(),
					),
				),
			)
		);
		$this->assertStringContainsString( '<details id="a1" class="imm-accordion__item" open>', $output );
	}

	public function testRenderMultiOpenFlag(): void {
		$output = $this->block->render(
			array(
				'items'      => array(),
				'multi_open' => true,
			)
		);
		$this->assertStringContainsString( 'data-multi-open="1"', $output );
	}

	public function testRenderIncludesChildBlockHtml(): void {
		$output = $this->block->render(
			array(
				'items' => array(
					array(
						'id'     => 'a1',
						'label'  => 'A',
						'blocks' => array(
							array(
								'type'   => 'text',
								'config' => array( 'content' => 'Hello child' ),
							),
						),
					),
				),
			)
		);
		$this->assertStringContainsString( 'imm-block--text', $output );
		$this->assertStringContainsString( 'Hello child', $output );
	}
}
