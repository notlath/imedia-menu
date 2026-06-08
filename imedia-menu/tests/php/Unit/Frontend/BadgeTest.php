<?php

declare(strict_types=1);

namespace IMedia\Menu\Unit\Frontend;

use IMedia\Menu\Frontend\Badge;
use PHPUnit\Framework\TestCase;

final class BadgeTest extends TestCase {

	protected function setUp(): void {
		$GLOBALS['_post_meta'] = array();
	}

	protected function tearDown(): void {
		$GLOBALS['_post_meta'] = array();
	}

	public function testDisabledByDefault(): void {
		$result = Badge::render( 1 );
		$this->assertSame( '', $result );
	}

	public function testEmptyTextReturnsEmpty(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style' => 'style-1',
			'_imedia_menu_badge_text'  => '',
		);

		$result = Badge::render( 1 );
		$this->assertSame( '', $result );
	}

	public function testRendersBasicBadge(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style' => 'style-1',
			'_imedia_menu_badge_text'  => 'New',
		);

		$result = Badge::render( 1 );

		$this->assertStringContainsString( 'imm-badge', $result );
		$this->assertStringContainsString( 'imm-badge--style-1', $result );
		$this->assertStringContainsString( 'data-style="style-1"', $result );
		$this->assertStringContainsString( '>New</span>', $result );
	}

	public function testAllFourStyles(): void {
		foreach ( array( 'style-1', 'style-2', 'style-3', 'style-4' ) as $style ) {
			$GLOBALS['_post_meta'][1] = array(
				'_imedia_menu_badge_style' => $style,
				'_imedia_menu_badge_text'  => 'Test',
			);

			$result = Badge::render( 1 );
			$this->assertStringContainsString( "imm-badge--{$style}", $result );
		}
	}

	public function testHideOnMobile(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style'         => 'style-1',
			'_imedia_menu_badge_text'          => 'New',
			'_imedia_menu_badge_hide_mobile'   => 'true',
		);

		$result = Badge::render( 1 );

		$this->assertStringContainsString( 'imm-hide-on-mobile', $result );
	}

	public function testHideOnDesktop(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style'         => 'style-1',
			'_imedia_menu_badge_text'          => 'New',
			'_imedia_menu_badge_hide_desktop'  => 'true',
		);

		$result = Badge::render( 1 );

		$this->assertStringContainsString( 'imm-hide-on-desktop', $result );
	}

	public function testCustomColors(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style'      => 'style-1',
			'_imedia_menu_badge_text'       => 'Sale',
			'_imedia_menu_badge_color'      => '#ff5500',
			'_imedia_menu_badge_text_color' => '#000000',
		);

		$result = Badge::render( 1 );

		$this->assertStringContainsString( '--imm-badge-bg:#ff5500', $result );
		$this->assertStringContainsString( '--imm-badge-text:#000000', $result );
	}

	public function testInvalidStyleReturnsEmpty(): void {
		$GLOBALS['_post_meta'][1] = array(
			'_imedia_menu_badge_style' => 'style-99',
			'_imedia_menu_badge_text'  => 'Test',
		);

		$result = Badge::render( 1 );
		$this->assertSame( '', $result );
	}

	public function testStylesConstantIncludesDisabled(): void {
		$this->assertContains( 'disabled', Badge::STYLES );
		$this->assertContains( 'style-1', Badge::STYLES );
		$this->assertContains( 'style-4', Badge::STYLES );
		$this->assertCount( 5, Badge::STYLES );
	}
}
