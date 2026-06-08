<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Fonts;

use IMedia\Menu\Fonts\FontsManager;
use PHPUnit\Framework\TestCase;

final class FontsManagerTest extends TestCase {

	private FontsManager $manager;

	protected function setUp(): void {
		$GLOBALS['_imedia_menu_options'] = array();
		$this->manager                   = new FontsManager();
		parent::setUp();
	}

	protected function tearDown(): void {
		unset( $GLOBALS['_imedia_menu_options'] );
		parent::tearDown();
	}

	public function testGetEnabledFontsEmpty(): void {
		$this->assertSame( array(), $this->manager->getEnabledFonts() );
	}

	public function testGetEnabledFontsWithData(): void {
		$fonts = array( 'Open Sans' => array( 'weights' => array( 400 ) ) );
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'] = $fonts;

		$this->assertSame( $fonts, $this->manager->getEnabledFonts() );
	}

	public function testGetEnabledFontsNonArrayReturnsEmpty(): void {
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'] = 'invalid';

		$this->assertSame( array(), $this->manager->getEnabledFonts() );
	}

	public function testSaveFonts(): void {
		$fonts = array( 'Roboto' => array( 'weights' => array( 400, 700 ) ) );

		$this->manager->saveFonts( $fonts );

		$saved = $GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'];
		$this->assertSame( $fonts, $saved );
	}

	public function testSaveFontsOverwritesPreviousValue(): void {
		$first  = array( 'Roboto' => array( 'weights' => array( 400 ) ) );
		$second = array( 'Lato' => array( 'weights' => array( 700 ) ) );

		$this->manager->saveFonts( $first );
		$this->manager->saveFonts( $second );

		$saved = $GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'];
		$this->assertSame( $second, $saved );
	}

	public function testEnqueueNoFontsDoesNotThrow(): void {
		$this->expectNotToPerformAssertions();
		$this->manager->enqueue();
	}

	public function testEnqueueWithFontsDoesNotThrow(): void {
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'] = array(
			'Open Sans' => array( 'weights' => array( 400 ) ),
		);
		$this->expectNotToPerformAssertions();
		$this->manager->enqueue();
	}

	public function testEnqueueEmptyFontsArrayDoesNotThrow(): void {
		$GLOBALS['_imedia_menu_options']['imedia_menu_settings']['google_fonts'] = array();
		$this->expectNotToPerformAssertions();
		$this->manager->enqueue();
	}
}
