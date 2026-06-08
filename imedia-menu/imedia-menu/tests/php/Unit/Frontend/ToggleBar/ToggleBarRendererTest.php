<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\ToggleBarRenderer;
use IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository;
use IMedia\Menu\Frontend\ToggleBar\ToggleBlockRegistry;
use IMedia\Menu\Frontend\ToggleBar\Blocks\SpacerBlock;
use IMedia\Menu\Frontend\ToggleBar\Blocks\MenuToggleBlock;
use IMedia\Menu\Frontend\ToggleBar\Blocks\LogoBlock;
use PHPUnit\Framework\TestCase;

final class ToggleBarRendererTest extends TestCase {

	private ToggleBarRepository $repository;
	private ToggleBlockRegistry $registry;
	private ToggleBarRenderer $renderer;

	protected function setUp(): void {
		unset( $GLOBALS['_imedia_menu_options']['imedia_menu_toggle_bar'] );
		$this->repository = new ToggleBarRepository();
		$this->registry   = new ToggleBlockRegistry();
		$this->registry->register( new SpacerBlock() );
		$this->registry->register( new MenuToggleBlock() );
		$this->registry->register( new LogoBlock() );
		$this->renderer   = new ToggleBarRenderer( $this->repository, $this->registry );
	}

	public function testRenderReturnsEmptyStringWhenNoBlocks(): void {
		$html = $this->renderer->render( 'primary' );
		$this->assertSame( '', $html );
	}

	public function testRenderProducesWrapperDiv(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '10px' ) ),
		) );

		$html = $this->renderer->render( 'primary' );

		$this->assertStringContainsString( 'class="imm-toggle-bar"', $html );
		$this->assertStringContainsString( 'data-imm-toggle-bar-location="primary"', $html );
		$this->assertStringContainsString( '</div>', $html );
	}

	public function testRenderSortsBlocksIntoLeftCenterRightRegions(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '10px' ) ),
			array( 'id' => 'b2', 'type' => 'menu_toggle', 'align' => 'right', 'settings' => array() ),
		) );

		$html = $this->renderer->render( 'primary' );

		$leftPos  = strpos( $html, 'imm-toggle-bar-left' );
		$rightPos = strpos( $html, 'imm-toggle-bar-right' );
		$centerPos = strpos( $html, 'imm-toggle-bar-center' );

		$this->assertNotFalse( $leftPos );
		$this->assertNotFalse( $rightPos );
		$this->assertFalse( $centerPos, 'Center region should not render when empty' );
		$this->assertLessThan( $rightPos, $leftPos );
	}

	public function testRenderIncludesCenterRegionWhenConfigured(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array() ),
			array( 'id' => 'b2', 'type' => 'spacer', 'align' => 'center', 'settings' => array() ),
			array( 'id' => 'b3', 'type' => 'spacer', 'align' => 'right', 'settings' => array() ),
		) );

		$html = $this->renderer->render( 'primary' );

		$this->assertStringContainsString( 'imm-toggle-bar-center', $html );
		$this->assertStringContainsString( 'imm-toggle-bar--has-center', $html );
	}

	public function testRenderPreservesBlockOrderWithinRegion(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '10px' ) ),
			array( 'id' => 'b2', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '20px' ) ),
		) );

		$html = $this->renderer->render( 'primary' );

		$pos1 = strpos( $html, 'width:10px' );
		$pos2 = strpos( $html, 'width:20px' );

		$this->assertNotFalse( $pos1 );
		$this->assertNotFalse( $pos2 );
		$this->assertLessThan( $pos2, $pos1 );
	}

	public function testRenderInvokesBlockRenderForEachBlock(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '15px' ) ),
			array( 'id' => 'b2', 'type' => 'menu_toggle', 'align' => 'right', 'settings' => array() ),
		) );

		$html = $this->renderer->render( 'primary' );

		$this->assertStringContainsString( 'imm-toggle-block--spacer', $html );
		$this->assertStringContainsString( 'imm-toggle-block--menu-toggle', $html );
		$this->assertStringContainsString( 'width:15px', $html );
	}

	public function testRenderSkipsUnknownBlockTypes(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array( 'width' => '10px' ) ),
		) );

		$html = $this->renderer->render( 'primary' );

		$this->assertStringContainsString( 'imm-toggle-block--spacer', $html );
		$this->assertStringNotContainsString( 'unknown', $html );
	}

	public function testHasBlocksForLocationDelegatesToRepository(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array() ),
		) );

		$this->assertTrue( $this->renderer->hasBlocksForLocation( 'primary' ) );
		$this->assertFalse( $this->renderer->hasBlocksForLocation( 'footer' ) );
	}

	public function testRenderForAllLocationsCombinesAllBars(): void {
		$this->repository->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'spacer', 'align' => 'left', 'settings' => array() ),
		) );
		$this->repository->save( 'footer', array(
			array( 'id' => 'b2', 'type' => 'spacer', 'align' => 'left', 'settings' => array() ),
		) );

		$html = $this->renderer->renderForAllLocations();

		$this->assertStringContainsString( 'data-imm-toggle-bar-location="primary"', $html );
		$this->assertStringContainsString( 'data-imm-toggle-bar-location="footer"', $html );
	}
}