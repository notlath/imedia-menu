<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Frontend\PanelLayout\GridLayout;
use IMedia\Menu\Visibility\ConditionEvaluator;
use PHPUnit\Framework\TestCase;

final class GridLayoutTest extends TestCase {

	private GridLayout $layout;
	private Registry $registry;
	private ConditionEvaluator $evaluator;

	protected function setUp(): void {
		$this->registry  = new Registry();
		$this->evaluator = new ConditionEvaluator();
		$this->layout    = new GridLayout( $this->registry, $this->evaluator );
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

	public function testRenderProducesImmRowGridAndImmColGrid(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array( 'span' => 6, 'blocks' => array() ),
							array( 'span' => 6, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'imm-row--grid', $html );
		$this->assertStringContainsString( 'imm-col--grid', $html );
		$this->assertStringContainsString( '--imm-row-tracks:12', $html );
		$this->assertStringContainsString( 'grid-column:span 6', $html );
	}

	public function testRenderAppliesCustomRowTracks(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'tracks'  => 6,
						'columns' => array(
							array( 'span' => 3, 'blocks' => array() ),
							array( 'span' => 3, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( '--imm-row-tracks:6', $html );
	}

	public function testRenderClampsRowTracksToMaxTwelve(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'tracks'  => 20,
						'columns' => array(
							array( 'span' => 6, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( '--imm-row-tracks:12', $html );
	}

	public function testRenderClampsRowTracksToMinOne(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'tracks'  => 0,
						'columns' => array(
							array( 'span' => 12, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( '--imm-row-tracks:1', $html );
	}

	public function testRenderClampsSpanToMaxTwelve(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array( 'span' => 18, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'grid-column:span 12', $html );
	}

	public function testRenderClampsSpanToMinOne(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array( 'span' => 0, 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'grid-column:span 1', $html );
	}

	public function testRenderDefaultsSpanByColumnCount(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array( 'blocks' => array() ),
							array( 'blocks' => array() ),
							array( 'blocks' => array() ),
						),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		// 3 columns, 12/3 = 4
		$this->assertStringContainsString( 'grid-column:span 4', $html );
		$this->assertSame( 3, substr_count( $html, 'grid-column:span 4' ) );
	}

	public function testRenderDefaultsSpanToTwelveWhenColumnCountZero(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array( 'columns' => array() ),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		// No columns -> empty <div class="imm-row--grid"></div>
		$this->assertStringContainsString( 'imm-row--grid', $html );
	}

	public function testRenderAppliesHideOnMobileClass(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'meta'    => array( 'hide_on_mobile' => true ),
						'columns' => array( array( 'span' => 12, 'blocks' => array() ) ),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'imm-row--hide-mobile', $html );
		$this->assertStringNotContainsString( 'imm-row--hide-desktop', $html );
	}

	public function testRenderAppliesHideOnDesktopClass(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'meta'    => array( 'hide_on_desktop' => true ),
						'columns' => array( array( 'span' => 12, 'blocks' => array() ) ),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'imm-row--hide-desktop', $html );
		$this->assertStringNotContainsString( 'imm-row--hide-mobile', $html );
	}

	public function testRenderAppliesBothHideClasses(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'meta'    => array(
							'hide_on_mobile' => true,
							'hide_on_desktop' => true,
						),
						'columns' => array( array( 'span' => 12, 'blocks' => array() ) ),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'imm-row--hide-mobile', $html );
		$this->assertStringContainsString( 'imm-row--hide-desktop', $html );
	}

	public function testRenderAppliesCustomCssClassViaMeta(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'meta'    => array( 'css_class' => 'my-custom-row' ),
						'columns' => array( array( 'span' => 12, 'blocks' => array() ) ),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		$this->assertStringContainsString( 'my-custom-row', $html );
	}

	public function testRenderSanitizesCssClassViaSanitizeHtmlClass(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'meta'    => array( 'css_class' => 'bad/../class!"name' ),
						'columns' => array( array( 'span' => 12, 'blocks' => array() ) ),
					),
				),
			),
		);

		$html = $this->layout->render( $panel, 1 );

		// sanitize_html_class strips slashes, dots, quotes, and bangs.
		$this->assertStringNotContainsString( 'bad/../class!', $html );
		$this->assertStringNotContainsString( '"name', $html );
		// But alpha chars survive; the output contains a sanitized version.
		$this->assertStringContainsString( 'badclassname', $html );
	}

	public function testRenderRendersBlockContent(): void {
		$panel = (object) array(
			'menu_item_id' => 1,
			'config'       => array(
				'rows' => array(
					array(
						'columns' => array(
							array(
								'span'   => 12,
								'blocks' => array(
									array(
										'type'   => 'text',
										'config' => array(
											'content' => 'GridBlockContent',
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

		$this->assertStringContainsString( 'GridBlockContent', $html );
	}

	public function testRequiredStylesheetIsImmGridCss(): void {
		$this->assertSame( 'imm-grid.css', $this->layout->requiredStylesheet() );
	}

	public function testResolveSpanWithExplicitValue(): void {
		$this->assertSame( 4, GridLayout::resolveSpan( array( 'span' => 4 ), 0 ) );
		$this->assertSame( 12, GridLayout::resolveSpan( array( 'span' => 12 ), 0 ) );
		$this->assertSame( 1, GridLayout::resolveSpan( array( 'span' => 1 ), 0 ) );
	}

	public function testResolveSpanClampsExplicitValue(): void {
		$this->assertSame( 12, GridLayout::resolveSpan( array( 'span' => 99 ), 0 ) );
		$this->assertSame( 1, GridLayout::resolveSpan( array( 'span' => -5 ), 0 ) );
		$this->assertSame( 1, GridLayout::resolveSpan( array( 'span' => 0 ), 0 ) );
	}

	public function testResolveSpanDefaultsByColumnCount(): void {
		$this->assertSame( 12, GridLayout::resolveSpan( array(), 1 ) );
		$this->assertSame( 6, GridLayout::resolveSpan( array(), 2 ) );
		$this->assertSame( 4, GridLayout::resolveSpan( array(), 3 ) );
		$this->assertSame( 3, GridLayout::resolveSpan( array(), 4 ) );
		$this->assertSame( 1, GridLayout::resolveSpan( array(), 12 ) );
	}

	public function testResolveSpanReturnsTwelveWhenColumnCountIsZeroOrNegative(): void {
		$this->assertSame( 12, GridLayout::resolveSpan( array(), 0 ) );
		$this->assertSame( 12, GridLayout::resolveSpan( array(), -1 ) );
	}
}
