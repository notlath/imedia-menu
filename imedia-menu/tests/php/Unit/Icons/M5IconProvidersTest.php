<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Icons;

use IMedia\Menu\Icons\Providers\FontAwesome5Provider;
use IMedia\Menu\Icons\Providers\FontAwesome6Provider;
use IMedia\Menu\Icons\Providers\GenericonsProvider;
use IMedia\Menu\Icons\Providers\BootstrapIconsProvider;
use PHPUnit\Framework\TestCase;

final class M5IconProvidersTest extends TestCase {

	public function testFontAwesome5ProviderId(): void {
		$provider = new FontAwesome5Provider();
		$this->assertSame( 'fa5', $provider->id() );
	}

	public function testFontAwesome5ProviderName(): void {
		$provider = new FontAwesome5Provider();
		$this->assertNotEmpty( $provider->name() );
	}

	public function testFontAwesome5ProviderIconCount(): void {
		$provider = new FontAwesome5Provider();
		$this->assertGreaterThanOrEqual( 100, count( $provider->getAvailableIcons() ) );
	}

	public function testFontAwesome5GetIconDefault(): void {
		$provider = new FontAwesome5Provider();
		$icon     = $provider->getIcon( 'home' );
		$this->assertStringContainsString( 'fas fa-home', $icon );
		$this->assertStringContainsString( 'imm-icon--fa5', $icon );
		$this->assertStringContainsString( 'aria-hidden', $icon );
	}

	public function testFontAwesome5GetIconBrands(): void {
		$provider = new FontAwesome5Provider();
		$icon     = $provider->getIcon( 'brands facebook' );
		$this->assertStringContainsString( 'fab fa-facebook', $icon );
	}

	public function testFontAwesome5GetIconRegular(): void {
		$provider = new FontAwesome5Provider();
		$icon     = $provider->getIcon( 'regular star' );
		$this->assertStringContainsString( 'far fa-star', $icon );
	}

	public function testFontAwesome6ProviderId(): void {
		$provider = new FontAwesome6Provider();
		$this->assertSame( 'fa6', $provider->id() );
	}

	public function testFontAwesome6ProviderName(): void {
		$provider = new FontAwesome6Provider();
		$this->assertNotEmpty( $provider->name() );
	}

	public function testFontAwesome6ProviderIconCount(): void {
		$provider = new FontAwesome6Provider();
		$this->assertGreaterThanOrEqual( 100, count( $provider->getAvailableIcons() ) );
	}

	public function testFontAwesome6GetIconDefault(): void {
		$provider = new FontAwesome6Provider();
		$icon     = $provider->getIcon( 'house' );
		$this->assertStringContainsString( 'fa-solid fa-house', $icon );
		$this->assertStringContainsString( 'imm-icon--fa6', $icon );
	}

	public function testFontAwesome6GetIconBrands(): void {
		$provider = new FontAwesome6Provider();
		$icon     = $provider->getIcon( 'brands twitter' );
		$this->assertStringContainsString( 'fa-brands fa-twitter', $icon );
	}

	public function testFontAwesome6GetIconRegular(): void {
		$provider = new FontAwesome6Provider();
		$icon     = $provider->getIcon( 'regular star' );
		$this->assertStringContainsString( 'fa-regular fa-star', $icon );
	}

	public function testFontAwesome6GetIconLight(): void {
		$provider = new FontAwesome6Provider();
		$icon     = $provider->getIcon( 'light heart' );
		$this->assertStringContainsString( 'fa-light fa-heart', $icon );
	}

	public function testGenericonsProviderId(): void {
		$provider = new GenericonsProvider();
		$this->assertSame( 'genericons', $provider->id() );
	}

	public function testGenericonsProviderName(): void {
		$provider = new GenericonsProvider();
		$this->assertNotEmpty( $provider->name() );
	}

	public function testGenericonsProviderIconCount(): void {
		$provider = new GenericonsProvider();
		$this->assertGreaterThan( 70, count( $provider->getAvailableIcons() ) );
	}

	public function testGenericonsGetIcon(): void {
		$provider = new GenericonsProvider();
		$icon     = $provider->getIcon( 'home' );
		$this->assertStringContainsString( 'genericon genericon-home', $icon );
		$this->assertStringContainsString( 'imm-icon--genericons', $icon );
	}

	public function testBootstrapIconsProviderId(): void {
		$provider = new BootstrapIconsProvider();
		$this->assertSame( 'bootstrap-icons', $provider->id() );
	}

	public function testBootstrapIconsProviderName(): void {
		$provider = new BootstrapIconsProvider();
		$this->assertNotEmpty( $provider->name() );
	}

	public function testBootstrapIconsProviderIconCount(): void {
		$provider = new BootstrapIconsProvider();
		$this->assertGreaterThanOrEqual( 99, count( $provider->getAvailableIcons() ) );
	}

	public function testBootstrapIconsGetIcon(): void {
		$provider = new BootstrapIconsProvider();
		$icon     = $provider->getIcon( 'house-door' );
		$this->assertStringContainsString( 'bi-house-door', $icon );
		$this->assertStringContainsString( 'imm-icon--bootstrap-icons', $icon );
	}

	public function testBootstrapIconsGetIconAriaHidden(): void {
		$provider = new BootstrapIconsProvider();
		$icon     = $provider->getIcon( 'search' );
		$this->assertStringContainsString( 'aria-hidden', $icon );
	}
}
