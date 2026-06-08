<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Fonts;

use IMedia\Menu\Admin\Settings\Tabs\FontsTab;
use PHPUnit\Framework\TestCase;

final class FontsTabTest extends TestCase {

	private FontsTab $tab;

	protected function setUp(): void {
		$this->tab = new FontsTab();
	}

	public function testId(): void {
		$this->assertSame( 'fonts', $this->tab->id() );
	}

	public function testLabel(): void {
		$this->assertNotEmpty( $this->tab->label() );
	}

	public function testValidateEmptyInput(): void {
		$result = $this->tab->validate( array() );
		$this->assertSame( array(), $result );
	}

	public function testValidateSingleFont(): void {
		$result = $this->tab->validate(
			array(
				'google_fonts' => array(
					'Open Sans' => array(
						'weights' => array( '400', '700' ),
						'active'  => '1',
					),
				),
			)
		);

		$this->assertArrayHasKey( 'Open Sans', $result );
		$this->assertSame( array( 400, 700 ), $result['Open Sans']['weights'] );
	}

	public function testValidateRejectsUnknownFont(): void {
		$result = $this->tab->validate(
			array(
				'google_fonts' => array(
					'NonExistentFontName' => array(
						'weights' => array( '400' ),
						'active'  => '1',
					),
				),
			)
		);

		$this->assertArrayNotHasKey( 'NonExistentFontName', $result );
	}

	public function testValidateRejectsInvalidWeight(): void {
		$result = $this->tab->validate(
			array(
				'google_fonts' => array(
					'Open Sans' => array(
						'weights' => array( '400', '99999' ),
						'active'  => '1',
					),
				),
			)
		);

		$this->assertArrayHasKey( 'Open Sans', $result );
		$this->assertSame( array( 400 ), $result['Open Sans']['weights'] );
	}

	public function testValidateSkipsInactiveFont(): void {
		$result = $this->tab->validate(
			array(
				'google_fonts' => array(
					'Open Sans' => array(
						'weights' => array( '400' ),
					),
				),
			)
		);

		$this->assertArrayNotHasKey( 'Open Sans', $result );
	}

	public function testValidateMultipleFonts(): void {
		$result = $this->tab->validate(
			array(
				'google_fonts' => array(
					'Open Sans' => array(
						'weights' => array( '400' ),
						'active'  => '1',
					),
					'Roboto'    => array(
						'weights' => array( '300', '700' ),
						'active'  => '1',
					),
				),
			)
		);

		$this->assertCount( 2, $result );
		$this->assertArrayHasKey( 'Open Sans', $result );
		$this->assertArrayHasKey( 'Roboto', $result );
	}

	public function testSanitizePassesThrough(): void {
		$input = array(
			'google_fonts' => array(
				'Open Sans' => array(
					'weights' => array( '400', '700' ),
					'active'  => '1',
				),
			),
		);

		$sanitized = $this->tab->sanitize( $input );
		$validated = $this->tab->validate( $input );

		$this->assertSame( $validated, $sanitized );
	}
}
