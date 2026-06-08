<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Admin\Settings;

use IMedia\Menu\Admin\Settings\PageRegistry;
use PHPUnit\Framework\TestCase;

final class PageRegistryTest extends TestCase {

	public function testGetTabIdsForGeneral(): void {
		$this->assertSame( array( 'general', 'animations' ), PageRegistry::getTabIds( 'imedia-menu' ) );
	}

	public function testGetTabIdsForDesign(): void {
		$this->assertSame( array( 'design', 'fonts' ), PageRegistry::getTabIds( 'imedia-menu-design' ) );
	}

	public function testGetTabIdsForMobile(): void {
		$this->assertSame( array( 'mobile', 'visibility' ), PageRegistry::getTabIds( 'imedia-menu-mobile' ) );
	}

	public function testGetTabIdsForIcons(): void {
		$this->assertSame( array( 'icons', 'performance' ), PageRegistry::getTabIds( 'imedia-menu-icons' ) );
	}

	public function testGetTabIdsForAdvanced(): void {
		$this->assertSame( array( 'advanced' ), PageRegistry::getTabIds( 'imedia-menu-advanced' ) );
	}

	public function testGetTabIdsReturnsEmptyForUnknownSlug(): void {
		$this->assertSame( array(), PageRegistry::getTabIds( 'unknown-slug' ) );
	}

	public function testGetTitleForGeneral(): void {
		$this->assertSame( 'General', PageRegistry::getTitle( 'imedia-menu' ) );
	}

	public function testGetTitleForDesign(): void {
		$this->assertSame( 'Design & Fonts', PageRegistry::getTitle( 'imedia-menu-design' ) );
	}

	public function testGetTitleReturnsEmptyForUnknownSlug(): void {
		$this->assertSame( '', PageRegistry::getTitle( 'unknown' ) );
	}

	public function testGetMenuLabelForGeneral(): void {
		$this->assertSame( 'iMedia Menu', PageRegistry::getMenuLabel( 'imedia-menu' ) );
	}

	public function testGetMenuLabelForOther(): void {
		$this->assertSame( 'Advanced', PageRegistry::getMenuLabel( 'imedia-menu-advanced' ) );
	}

	public function testGetPageTitleForGeneral(): void {
		$this->assertSame( 'iMedia Menu — General', PageRegistry::getPageTitle( 'imedia-menu' ) );
	}

	public function testGetPageTitleForDesign(): void {
		$this->assertStringContainsString( 'Design & Fonts', PageRegistry::getPageTitle( 'imedia-menu-design' ) );
	}

	public function testGetAllSlugs(): void {
		$slugs = PageRegistry::getAllSlugs();
		$this->assertIsArray( $slugs );
		$this->assertCount( 5, $slugs );
		$this->assertContains( 'imedia-menu', $slugs );
		$this->assertContains( 'imedia-menu-design', $slugs );
		$this->assertContains( 'imedia-menu-mobile', $slugs );
		$this->assertContains( 'imedia-menu-icons', $slugs );
		$this->assertContains( 'imedia-menu-advanced', $slugs );
	}
}
