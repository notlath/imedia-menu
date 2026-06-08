<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\MenuOrientation;
use PHPUnit\Framework\TestCase;

final class MenuOrientationTest extends TestCase {

	public function testAllCasesExist(): void {
		$values = array_map( fn ( MenuOrientation $o ) => $o->value, MenuOrientation::cases() );
		$this->assertContains( 'horizontal', $values );
		$this->assertContains( 'vertical', $values );
		$this->assertContains( 'accordion', $values );
	}

	public function testCount(): void {
		$this->assertCount( 3, MenuOrientation::cases() );
	}

	public function testAllCasesHaveLabels(): void {
		foreach ( MenuOrientation::cases() as $orientation ) {
			$this->assertNotEmpty( $orientation->label() );
		}
	}

	public function testAllCasesHaveDescriptions(): void {
		foreach ( MenuOrientation::cases() as $orientation ) {
			$this->assertNotEmpty( $orientation->description() );
		}
	}

	public function testTryFromValidValues(): void {
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::tryFrom( 'horizontal' ) );
		$this->assertSame( MenuOrientation::Vertical, MenuOrientation::tryFrom( 'vertical' ) );
		$this->assertSame( MenuOrientation::Accordion, MenuOrientation::tryFrom( 'accordion' ) );
	}

	public function testTryFromInvalidValueReturnsNull(): void {
		$this->assertNull( MenuOrientation::tryFrom( 'nonexistent' ) );
	}

	public function testFromStringOrDefaultWithNull(): void {
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::fromStringOrDefault( null ) );
	}

	public function testFromStringOrDefaultWithEmptyString(): void {
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::fromStringOrDefault( '' ) );
	}

	public function testFromStringOrDefaultWithValidValue(): void {
		$this->assertSame( MenuOrientation::Vertical, MenuOrientation::fromStringOrDefault( 'vertical' ) );
		$this->assertSame( MenuOrientation::Accordion, MenuOrientation::fromStringOrDefault( 'accordion' ) );
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::fromStringOrDefault( 'horizontal' ) );
	}

	public function testFromStringOrDefaultWithInvalidValueFallsBackToHorizontal(): void {
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::fromStringOrDefault( 'invalid' ) );
		$this->assertSame( MenuOrientation::Horizontal, MenuOrientation::fromStringOrDefault( 'VERTICAL' ) );
	}

	public function testRequiredTriggerTypeHorizontalIsNull(): void {
		$this->assertNull( MenuOrientation::Horizontal->requiredTriggerType() );
	}

	public function testRequiredTriggerTypeVerticalIsNull(): void {
		$this->assertNull( MenuOrientation::Vertical->requiredTriggerType() );
	}

	public function testRequiredTriggerTypeAccordionIsClick(): void {
		$this->assertSame( 'click', MenuOrientation::Accordion->requiredTriggerType() );
	}
}
