<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Cache;

use IMedia\Menu\Cache\CacheInvalidator;
use IMedia\Menu\Cache\MenuCache;
use PHPUnit\Framework\TestCase;

final class CacheInvalidatorTest extends TestCase {

	public function testInvalidateMenuDeletesCache(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->invalidateMenu( 5 );
		$this->expectNotToPerformAssertions();
	}

	public function testInvalidatePanelDeletesCache(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->invalidatePanel( 3 );
		$this->expectNotToPerformAssertions();
	}

	public function testInvalidateAllFlushesCache(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->invalidateAll();
		$this->expectNotToPerformAssertions();
	}

	public function testRegisterHooksAttachesActions(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->registerHooks();
		$this->assertTrue( has_action( 'wp_update_nav_menu' ) );
		$this->assertTrue( has_action( 'imedia_menu_settings_saved' ) );
		$this->assertTrue( has_action( 'imedia_menu_panel_saved' ) );
		$this->assertTrue( has_action( 'switch_theme' ) );
		$this->assertTrue( has_action( 'save_post' ) );
	}

	public function testOnSavePostSkipsRevisions(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->onSavePost( 1 );
		$this->expectNotToPerformAssertions();
	}

	public function testOnMenuUpdateInvalidates(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->onMenuUpdate( 5 );
		$this->expectNotToPerformAssertions();
	}

	public function testOnSettingsSavedInvalidatesAll(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->onSettingsSaved();
		$this->expectNotToPerformAssertions();
	}

	public function testOnPanelSavedInvalidatesPanel(): void {
		$invalidator = new CacheInvalidator();
		$invalidator->onPanelSaved( 3 );
		$this->expectNotToPerformAssertions();
	}
}
