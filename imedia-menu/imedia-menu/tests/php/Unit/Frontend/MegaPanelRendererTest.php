<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Frontend;

use IMedia\Menu\Frontend\MegaPanelRenderer;
use PHPUnit\Framework\TestCase;

final class MegaPanelRendererTest extends TestCase {

	public function testRequiredStylesheetsForEmptyReturnsArray(): void {
		$renderer = new MegaPanelRenderer();
		$result   = $renderer->requiredStylesheetsFor( array() );
		$this->assertIsArray( $result );
	}

	public function testRequiredStylesheetsForStandardReturnsArray(): void {
		$renderer = new MegaPanelRenderer();
		$result   = $renderer->requiredStylesheetsFor( array( 'columns' ) );
		$this->assertIsArray( $result );
	}

	public function testRequiredStylesheetsForGridReturnsArray(): void {
		$renderer = new MegaPanelRenderer();
		$result   = $renderer->requiredStylesheetsFor( array( 'grid' ) );
		$this->assertIsArray( $result );
	}
}
