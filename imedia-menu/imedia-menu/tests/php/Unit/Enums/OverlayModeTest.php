<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\OverlayMode;
use PHPUnit\Framework\TestCase;

final class OverlayModeTest extends TestCase {

	public function testAllCasesExist(): void {
		$values = array_map( fn ( OverlayMode $m ) => $m->value, OverlayMode::cases() );
		$this->assertContains( 'off', $values );
		$this->assertContains( 'desktop', $values );
		$this->assertContains( 'mobile', $values );
		$this->assertContains( 'both', $values );
	}

	public function testCount(): void {
		$this->assertCount( 4, OverlayMode::cases() );
	}

	public function testAllCasesHaveLabels(): void {
		foreach ( OverlayMode::cases() as $mode ) {
			$this->assertNotEmpty( $mode->label() );
		}
	}

	public function testTryFromValidValues(): void {
		$this->assertSame( OverlayMode::Off, OverlayMode::tryFrom( 'off' ) );
		$this->assertSame( OverlayMode::Desktop, OverlayMode::tryFrom( 'desktop' ) );
		$this->assertSame( OverlayMode::Mobile, OverlayMode::tryFrom( 'mobile' ) );
		$this->assertSame( OverlayMode::Both, OverlayMode::tryFrom( 'both' ) );
	}

	public function testTryFromInvalidValueReturnsNull(): void {
		$this->assertNull( OverlayMode::tryFrom( 'nonexistent' ) );
	}

	public function testFromStringOrDefaultWithNull(): void {
		$this->assertSame( OverlayMode::Off, OverlayMode::fromStringOrDefault( null ) );
	}

	public function testFromStringOrDefaultWithEmptyString(): void {
		$this->assertSame( OverlayMode::Off, OverlayMode::fromStringOrDefault( '' ) );
	}

	public function testFromStringOrDefaultWithValidValue(): void {
		$this->assertSame( OverlayMode::Desktop, OverlayMode::fromStringOrDefault( 'desktop' ) );
		$this->assertSame( OverlayMode::Mobile, OverlayMode::fromStringOrDefault( 'mobile' ) );
		$this->assertSame( OverlayMode::Both, OverlayMode::fromStringOrDefault( 'both' ) );
	}

	public function testFromStringOrDefaultWithInvalidValueFallsBackToOff(): void {
		$this->assertSame( OverlayMode::Off, OverlayMode::fromStringOrDefault( 'invalid' ) );
		$this->assertSame( OverlayMode::Off, OverlayMode::fromStringOrDefault( 'DESKTOP' ) );
	}

	public function testAppliesToOffIsAlwaysFalse(): void {
		$this->assertFalse( OverlayMode::Off->appliesTo( 'desktop' ) );
		$this->assertFalse( OverlayMode::Off->appliesTo( 'mobile' ) );
	}

	public function testAppliesToDesktopOnly(): void {
		$this->assertTrue( OverlayMode::Desktop->appliesTo( 'desktop' ) );
		$this->assertFalse( OverlayMode::Desktop->appliesTo( 'mobile' ) );
	}

	public function testAppliesToMobileOnly(): void {
		$this->assertFalse( OverlayMode::Mobile->appliesTo( 'desktop' ) );
		$this->assertTrue( OverlayMode::Mobile->appliesTo( 'mobile' ) );
	}

	public function testAppliesToBoth(): void {
		$this->assertTrue( OverlayMode::Both->appliesTo( 'desktop' ) );
		$this->assertTrue( OverlayMode::Both->appliesTo( 'mobile' ) );
	}
}
