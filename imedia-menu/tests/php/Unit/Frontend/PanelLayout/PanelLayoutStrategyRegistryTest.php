<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Enums\PanelLayoutType;
use IMedia\Menu\Frontend\PanelLayout\FlyoutLayout;
use IMedia\Menu\Frontend\PanelLayout\GridLayout;
use IMedia\Menu\Frontend\PanelLayout\PanelLayoutStrategyRegistry;
use IMedia\Menu\Frontend\PanelLayout\StandardLayout;
use IMedia\Menu\Visibility\ConditionEvaluator;
use PHPUnit\Framework\TestCase;

final class PanelLayoutStrategyRegistryTest extends TestCase {

	private PanelLayoutStrategyRegistry $registry;
	private Registry $contentRegistry;
	private ConditionEvaluator $evaluator;

	protected function setUp(): void {
		$this->contentRegistry = new Registry();
		$this->evaluator        = new ConditionEvaluator();
		$this->registry         = new PanelLayoutStrategyRegistry( $this->contentRegistry, $this->evaluator );
	}

	public function testReturnsStandardLayoutForStandardType(): void {
		$this->assertInstanceOf( StandardLayout::class, $this->registry->get( PanelLayoutType::Standard ) );
	}

	public function testReturnsGridLayoutForGridType(): void {
		$this->assertInstanceOf( GridLayout::class, $this->registry->get( PanelLayoutType::Grid ) );
	}

	public function testReturnsFlyoutLayoutForFlyoutType(): void {
		$this->assertInstanceOf( FlyoutLayout::class, $this->registry->get( PanelLayoutType::Flyout ) );
	}

	public function testGetReturnsSameInstanceOnRepeatedCalls(): void {
		$first  = $this->registry->get( PanelLayoutType::Grid );
		$second = $this->registry->get( PanelLayoutType::Grid );

		$this->assertSame( $first, $second );
	}

	public function testGetReturnsDifferentInstancesForDifferentLayouts(): void {
		$grid     = $this->registry->get( PanelLayoutType::Grid );
		$standard = $this->registry->get( PanelLayoutType::Standard );

		$this->assertNotSame( $grid, $standard );
	}

	public function testRequiredStylesheetsWithEmptyArrayReturnsEmpty(): void {
		$this->assertSame( array(), $this->registry->requiredStylesheets( array() ) );
	}

	public function testRequiredStylesheetsForStandardReturnsEmpty(): void {
		$this->assertSame( array(), $this->registry->requiredStylesheets( array( PanelLayoutType::Standard ) ) );
	}

	public function testRequiredStylesheetsForFlyoutReturnsEmpty(): void {
		$this->assertSame( array(), $this->registry->requiredStylesheets( array( PanelLayoutType::Flyout ) ) );
	}

	public function testRequiredStylesheetsForGridReturnsImmGridCss(): void {
		$this->assertSame( array( 'imm-grid.css' ), $this->registry->requiredStylesheets( array( PanelLayoutType::Grid ) ) );
	}

	public function testRequiredStylesheetsDeduplicates(): void {
		$files = $this->registry->requiredStylesheets( array(
			PanelLayoutType::Grid,
			PanelLayoutType::Grid,
			PanelLayoutType::Standard,
		) );

		$this->assertSame( array( 'imm-grid.css' ), $files );
	}

	public function testRequiredStylesheetsMixedReturnsAllUnique(): void {
		$files = $this->registry->requiredStylesheets( array(
			PanelLayoutType::Grid,
			PanelLayoutType::Standard,
			PanelLayoutType::Flyout,
		) );

		$this->assertSame( array( 'imm-grid.css' ), $files );
	}
}
