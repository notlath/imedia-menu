<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Fonts;

use IMedia\Menu\Fonts\GoogleFontsProvider;
use PHPUnit\Framework\TestCase;

final class GoogleFontsProviderTest extends TestCase {

	public function testGetFontsReturnsArray(): void {
		$fonts = GoogleFontsProvider::getFonts();
		$this->assertIsArray( $fonts );
	}

	public function testGetFontsHasCommonFonts(): void {
		$fonts = GoogleFontsProvider::getFonts();
		$this->assertContains( 'Open Sans', $fonts );
		$this->assertContains( 'Roboto', $fonts );
		$this->assertContains( 'Lato', $fonts );
		$this->assertContains( 'Montserrat', $fonts );
	}

	public function testGetFontsCount(): void {
		$fonts = GoogleFontsProvider::getFonts();
		$this->assertGreaterThanOrEqual( 300, count( $fonts ) );
	}

	public function testGetWeights(): void {
		$weights = GoogleFontsProvider::getWeights();
		$this->assertSame( array( 100, 200, 300, 400, 500, 600, 700, 800, 900 ), $weights );
	}

	public function testGetSubsets(): void {
		$subsets = GoogleFontsProvider::getSubsets();
		$this->assertArrayHasKey( 'latin', $subsets );
		$this->assertArrayHasKey( 'cyrillic', $subsets );
		$this->assertArrayHasKey( 'arabic', $subsets );
	}

	public function testGetFontUrlReturnsEmptyForEmptyInput(): void {
		$this->assertSame( '', GoogleFontsProvider::getFontUrl( array() ) );
	}

	public function testGetFontUrlSingleFont(): void {
		$url = GoogleFontsProvider::getFontUrl(
			array(
				'Open Sans' => array( 'weights' => array( 400, 700 ) ),
			)
		);

		$this->assertStringContainsString( 'family=Open+Sans:400,700', $url );
		$this->assertStringContainsString( 'display=swap', $url );
		$this->assertStringContainsString( 'fonts.googleapis.com/css2', $url );
	}

	public function testGetFontUrlMultipleFonts(): void {
		$url = GoogleFontsProvider::getFontUrl(
			array(
				'Open Sans' => array( 'weights' => array( 400 ) ),
				'Roboto'    => array( 'weights' => array( 300, 700 ) ),
			)
		);

		$this->assertStringContainsString( 'Open+Sans:400|Roboto:300,700', $url );
	}

	public function testGetFontUrlDefaultWeights(): void {
		$url = GoogleFontsProvider::getFontUrl(
			array(
				'Lato' => array(),
			)
		);

		$this->assertStringContainsString( 'Lato:400,700', $url );
	}

	public function testGetFontUrlSpecialCharacters(): void {
		$url = GoogleFontsProvider::getFontUrl(
			array(
				'Abril Fatface' => array( 'weights' => array( 400 ) ),
			)
		);

		$this->assertStringContainsString( 'Abril+Fatface:400', $url );
	}

	public function testGetFontUrlFilterable(): void {
		$filterCalled = false;
		add_filter(
			'imm_google_fonts_url',
			function ( string $query ) use ( &$filterCalled ): string {
				$filterCalled = true;
				return $query . '&text=Custom';
			}
		);

		$url = GoogleFontsProvider::getFontUrl(
			array(
				'Open Sans' => array( 'weights' => array( 400 ) ),
			)
		);

		$this->assertTrue( $filterCalled );
		$this->assertStringContainsString( 'text=Custom', $url );

		unset( $GLOBALS['__wp_filters']['imm_google_fonts_url'] );
	}
}
