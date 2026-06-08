<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository;
use PHPUnit\Framework\TestCase;

final class ToggleBarRepositoryTest extends TestCase {

	protected function setUp(): void {
		unset( $GLOBALS['_imedia_menu_options']['imedia_menu_toggle_bar'] );
	}

	public function testGetReturnsEmptyArrayWhenNoBlocks(): void {
		$repo = new ToggleBarRepository();
		$blocks = $repo->get( 'primary' );

		$this->assertIsArray( $blocks );
		$this->assertEmpty( $blocks );
	}

	public function testSavePersistsBlocksForLocation(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array(
				'id'       => 'b1',
				'type'     => 'logo',
				'align'    => 'left',
				'settings' => array( 'logo_id' => 42, 'url' => '/', 'target' => '_self', 'alt' => 'Home' ),
			),
		) );

		$blocks = $repo->get( 'primary' );

		$this->assertCount( 1, $blocks );
		$this->assertSame( 'logo', $blocks[0]['type'] );
		$this->assertSame( 'left', $blocks[0]['align'] );
		$this->assertSame( 42, $blocks[0]['settings']['logo_id'] );
	}

	public function testSaveValidatesBlockTypeAndRejectsInvalid(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array(
				'id'       => 'b1',
				'type'     => 'invalid_type',
				'align'    => 'left',
				'settings' => array(),
			),
		) );

		$blocks = $repo->get( 'primary' );

		$this->assertEmpty( $blocks );
	}

	public function testSaveValidatesAlignAndDefaultsToLeft(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array(
				'id'       => 'b1',
				'type'     => 'spacer',
				'align'    => 'invalid',
				'settings' => array( 'width' => '10px' ),
			),
		) );

		$blocks = $repo->get( 'primary' );

		$this->assertCount( 1, $blocks );
		$this->assertSame( 'left', $blocks[0]['align'] );
	}

	public function testSaveMultipleLocationsAreIndependent(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
		) );
		$repo->save( 'footer', array(
			array( 'id' => 'b2', 'type' => 'search', 'align' => 'right', 'settings' => array() ),
		) );

		$primaryBlocks = $repo->get( 'primary' );
		$footerBlocks  = $repo->get( 'footer' );

		$this->assertCount( 1, $primaryBlocks );
		$this->assertCount( 1, $footerBlocks );
		$this->assertSame( 'logo', $primaryBlocks[0]['type'] );
		$this->assertSame( 'search', $footerBlocks[0]['type'] );
	}

	public function testHasBlocksReturnsTrueWhenLocationHasBlocks(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
		) );

		$this->assertTrue( $repo->hasBlocks( 'primary' ) );
		$this->assertFalse( $repo->hasBlocks( 'footer' ) );
	}

	public function testAnyLocationHasBlocksReturnsTrueWhenAnyLocationHasBlocks(): void {
		$repo = new ToggleBarRepository();

		$this->assertFalse( $repo->anyLocationHasBlocks() );

		$repo->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
		) );

		$this->assertTrue( $repo->anyLocationHasBlocks() );
	}

	public function testDeleteRemovesLocationBlocks(): void {
		$repo = new ToggleBarRepository();
		$repo->save( 'primary', array(
			array( 'id' => 'b1', 'type' => 'logo', 'align' => 'left', 'settings' => array() ),
		) );
		$repo->delete( 'primary' );

		$blocks = $repo->get( 'primary' );

		$this->assertEmpty( $blocks );
	}
}