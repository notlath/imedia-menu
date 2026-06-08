<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Frontend\PanelLayout\StandardLayout;
use IMedia\Menu\Visibility\ConditionEvaluator;
use PHPUnit\Framework\TestCase;

final class StandardLayoutTest extends TestCase {

	private StandardLayout $layout;
	private Registry $registry;
	private ConditionEvaluator $evaluator;

	protected function setUp(): void {
		$this->registry  = new Registry();
		$this->evaluator = new ConditionEvaluator();
		$this->layout    = new StandardLayout( $this->registry, $this->evaluator );
	}

	public function testRenderWithEmptyRowsReturnsEmptyString(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array( 'rows' => array() ),
		);

		$this->assertSame( '', $this->layout->render( $panel, 1 ) );
	}

	public function testRenderWithMissingRowsKeyReturnsEmptyString(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(),
		);

		$this->assertSame( '', $this->layout->render( $panel, 1 ) );
	}

	public function testRenderProducesImmRowAndImmCol(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array(
								'width'  => '50%',
								'blocks' => array(
									array(
										'type'   => 'text',
										'config' => array(
											'content' => 'Hello',
										),
									),
								),
							),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( '<div class="imm-row">', $html );
		$this->assertStringContainsString( '<div class="imm-col"', $html );
		$this->assertStringContainsString( '--imm-col-width:50%', $html );
		$this->assertStringContainsString( 'Hello', $html );
	}

	public function testRenderAppliesColumnPaddingFromStyles(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array(
								'width'  => 'auto',
								'styles' => array(
									'padding' => array(
										'top'    => '10px',
										'right'  => '20px',
										'bottom' => '10px',
										'left'   => '20px',
									),
								),
								'blocks' => array(),
							),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'padding:10px 20px 10px 20px', $html );
	}

	public function testRequiredStylesheetIsNull(): void {
		$this->assertNull( $this->layout->requiredStylesheet() );
	}

	public function testRenderWithMultipleColumns(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array( 'width' => '25%', 'blocks' => array() ),
							array( 'width' => '25%', 'blocks' => array() ),
							array( 'width' => '25%', 'blocks' => array() ),
							array( 'width' => '25%', 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertSame( 4, substr_count( $html, '<div class="imm-col"' ) );
		$this->assertSame( 1, substr_count( $html, '<div class="imm-row">' ) );
	}
}
