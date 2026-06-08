<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\PanelLayoutType;
use PHPUnit\Framework\TestCase;

final class PanelLayoutTypeTest extends TestCase {

	public function testAllCasesExist(): void {
		$values = array_map( fn ( PanelLayoutType $t ) => $t->value, PanelLayoutType::cases() );
		$this->assertContains( 'flyout', $values );
		$this->assertContains( 'columns', $values );
		$this->assertContains( 'grid', $values );
	}

	public function testCount(): void {
		$this->assertCount( 3, PanelLayoutType::cases() );
	}

	public function testAllCasesHaveLabels(): void {
		foreach ( PanelLayoutType::cases() as $layout ) {
			$this->assertNotEmpty( $layout->label() );
		}
	}

	public function testAllCasesHaveDescriptions(): void {
		foreach ( PanelLayoutType::cases() as $layout ) {
			$this->assertNotEmpty( $layout->description() );
		}
	}

	public function testTryFromValidValues(): void {
		$this->assertSame( PanelLayoutType::Flyout, PanelLayoutType::tryFrom( 'flyout' ) );
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::tryFrom( 'columns' ) );
		$this->assertSame( PanelLayoutType::Grid, PanelLayoutType::tryFrom( 'grid' ) );
	}

	public function testTryFromInvalidValueReturnsNull(): void {
		$this->assertNull( PanelLayoutType::tryFrom( 'nonexistent' ) );
	}

	public function testFromStringOrDefaultWithNull(): void {
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( null ) );
	}

	public function testFromStringOrDefaultWithEmptyString(): void {
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( '' ) );
	}

	public function testFromStringOrDefaultWithValidValue(): void {
		$this->assertSame( PanelLayoutType::Flyout, PanelLayoutType::fromStringOrDefault( 'flyout' ) );
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( 'columns' ) );
		$this->assertSame( PanelLayoutType::Grid, PanelLayoutType::fromStringOrDefault( 'grid' ) );
	}

	public function testFromStringOrDefaultWithInvalidValueFallsBackToStandard(): void {
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( 'invalid' ) );
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( '0' ) );
		$this->assertSame( PanelLayoutType::Standard, PanelLayoutType::fromStringOrDefault( 'COLUMNS' ) );
	}

	public function testFlyoutValue(): void {
		$this->assertSame( 'flyout', PanelLayoutType::Flyout->value );
	}

	public function testStandardValueIsColumnsForBackwardCompatibility(): void {
		// The DB column default is 'columns' (legacy value); Standard must match.
		$this->assertSame( 'columns', PanelLayoutType::Standard->value );
	}

	public function testGridValue(): void {
		$this->assertSame( 'grid', PanelLayoutType::Grid->value );
	}
}
