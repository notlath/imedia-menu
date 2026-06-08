<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\ContentBlocks\TabbedBlock;
use PHPUnit\Framework\TestCase;

final class TabbedBlockTest extends TestCase {

	private TabbedBlock $block;
	private Registry $registry;

	protected function setUp(): void {
		$this->block    = new TabbedBlock();
		$this->registry = new Registry();
		$this->block->setRegistry( $this->registry );
	}

	public function testType(): void {
		$this->assertSame( 'tabbed', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'tabs', $cfg );
		$this->assertArrayHasKey( 'orientation', $cfg );
		$this->assertArrayHasKey( 'default_tab', $cfg );
		$this->assertSame( 'horizontal', $cfg['orientation'] );
	}

	public function testRenderEmptyTabs(): void {
		$output = $this->block->render( array( 'tabs' => array() ) );
		$this->assertStringContainsString( 'imm-block--tabbed', $output );
		$this->assertStringContainsString( 'No tabs defined', $output );
	}

	public function testRenderAriaTablist(): void {
		$output = $this->block->render(
			array(
				'tabs' => array(
					array(
						'id'     => 't1',
						'label'  => 'Tab One',
						'blocks' => array(),
					),
					array(
						'id'     => 't2',
						'label'  => 'Tab Two',
						'blocks' => array(),
					),
				),
			)
		);
		$this->assertStringContainsString( 'role="tablist"', $output );
		$this->assertStringContainsString( 'aria-orientation="horizontal"', $output );
		$this->assertStringContainsString( 'role="tab"', $output );
		$this->assertStringContainsString( 'aria-selected="true"', $output );
		$this->assertStringContainsString( 'aria-selected="false"', $output );
		$this->assertStringContainsString( 'id="tab-t1"', $output );
		$this->assertStringContainsString( 'id="panel-t1"', $output );
		$this->assertStringContainsString( 'role="tabpanel"', $output );
	}

	public function testRenderVerticalOrientation(): void {
		$output = $this->block->render(
			array(
				'tabs'        => array(
					array(
						'id'     => 't1',
						'label'  => 'A',
						'blocks' => array(),
					),
				),
				'orientation' => 'vertical',
			)
		);
		$this->assertStringContainsString( 'imm-block--tabbed--vertical', $output );
		$this->assertStringContainsString( 'aria-orientation="vertical"', $output );
	}

	public function testRenderDefaultTabMarksFirstAsActive(): void {
		$output = $this->block->render(
			array(
				'tabs' => array(
					array(
						'id'     => 't1',
						'label'  => 'First',
						'blocks' => array(),
					),
					array(
						'id'     => 't2',
						'label'  => 'Second',
						'blocks' => array(),
					),
				),
			)
		);
		$this->assertStringContainsString( 'id="tab-t1" aria-controls="panel-t1" aria-selected="true" tabindex="0"', $output );
		$this->assertStringContainsString( 'id="tab-t2" aria-controls="panel-t2" aria-selected="false" tabindex="-1"', $output );
		$this->assertStringContainsString( 'id="panel-t1" aria-labelledby="tab-t1"', $output );
		$this->assertStringContainsString( 'id="panel-t2" aria-labelledby="tab-t2" hidden', $output );
	}

	public function testRenderExplicitDefaultTab(): void {
		$output = $this->block->render(
			array(
				'tabs'        => array(
					array(
						'id'     => 't1',
						'label'  => 'A',
						'blocks' => array(),
					),
					array(
						'id'     => 't2',
						'label'  => 'B',
						'blocks' => array(),
					),
				),
				'default_tab' => 't2',
			)
		);
		$this->assertStringContainsString( 'data-default-tab="t2"', $output );
		$this->assertStringContainsString( 'id="tab-t1"', $output );
		$this->assertStringContainsString( 'aria-selected="false"', $output );
		$this->assertStringContainsString( 'id="tab-t2"', $output );
		$this->assertStringContainsString( 'aria-selected="true"', $output );
	}

	public function testRenderIncludesChildBlockHtml(): void {
		$output = $this->block->render(
			array(
				'tabs' => array(
					array(
						'id'     => 't1',
						'label'  => 'A',
						'blocks' => array(
							array(
								'type'   => 'text',
								'config' => array( 'content' => 'Tab content' ),
							),
						),
					),
				),
			)
		);
		$this->assertStringContainsString( 'Tab content', $output );
	}
}
