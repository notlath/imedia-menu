<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\DynamicHtmlBlock;
use PHPUnit\Framework\TestCase;

final class DynamicHtmlBlockTest extends TestCase {

	private DynamicHtmlBlock $block;

	protected function setUp(): void {
		$this->block = new DynamicHtmlBlock();
	}

	public function testType(): void {
		$this->assertSame( 'dynamic_html', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'source', $cfg );
		$this->assertArrayHasKey( 'url', $cfg );
		$this->assertArrayHasKey( 'callback', $cfg );
		$this->assertArrayHasKey( 'method', $cfg );
		$this->assertArrayHasKey( 'cache_ttl', $cfg );
		$this->assertArrayHasKey( 'timeout', $cfg );
		$this->assertArrayHasKey( 'allowed_html', $cfg );
	}

	public function testRenderEmptyUrlReturnsUnavailable(): void {
		$output = $this->block->render(
			array(
				'source' => 'url',
				'url'    => '',
			)
		);
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
	}

	public function testRenderInvalidUrlReturnsUnavailable(): void {
		$output = $this->block->render(
			array(
				'source' => 'url',
				'url'    => 'not a url with spaces',
			)
		);
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
	}

	public function testRenderUnknownSource(): void {
		$output = $this->block->render( array( 'source' => 'ftp' ) );
		$this->assertStringContainsString( 'Unknown source type', $output );
	}

	public function testRenderInvalidCallback(): void {
		$output = $this->block->render(
			array(
				'source'   => 'callback',
				'callback' => 'NonExistentFunction_xyz',
			)
		);
		$this->assertStringContainsString( 'imm-block--unavailable', $output );
	}

	public function testRenderCallbackRendersOutput(): void {
		$output = $this->block->render(
			array(
				'source'   => 'callback',
				'callback' => array( self::class, 'staticCallbackForTest' ),
			)
		);
		$this->assertStringContainsString( 'CALLBACK_OUTPUT', $output );
	}

	public function testRenderSanitizesScript(): void {
		$output = $this->block->render(
			array(
				'source'   => 'callback',
				'callback' => array( self::class, 'maliciousCallbackForTest' ),
			)
		);
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public static function staticCallbackForTest(): string {
		return 'CALLBACK_OUTPUT';
	}

	public static function maliciousCallbackForTest(): string {
		return '<script>alert(1)</script>safe';
	}
}
