<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\BannerBlock;
use PHPUnit\Framework\TestCase;

final class BannerBlockTest extends TestCase {

	private BannerBlock $block;

	protected function setUp(): void {
		$this->block = new BannerBlock();
	}

	public function testType(): void {
		$this->assertSame( 'banner', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'image_id', $cfg );
		$this->assertArrayHasKey( 'title', $cfg );
		$this->assertArrayHasKey( 'text', $cfg );
		$this->assertArrayHasKey( 'link', $cfg );
		$this->assertArrayHasKey( 'button_text', $cfg );
		$this->assertArrayHasKey( 'alt', $cfg );
		$this->assertArrayHasKey( 'template', $cfg );
		$this->assertArrayHasKey( 'overlay_color', $cfg );
		$this->assertArrayHasKey( 'overlay_opacity', $cfg );
		$this->assertArrayHasKey( 'cta', $cfg );
		$this->assertArrayHasKey( 'aspect_ratio', $cfg );
		$this->assertSame( 'overlay', $cfg['template'] );
	}

	public function testRenderEmptyConfig(): void {
		$output = $this->block->render( array() );
		$this->assertStringContainsString( 'imm-block--banner', $output );
		$this->assertStringContainsString( 'imm-block--banner--overlay', $output );
	}

	public function testRenderWithTitle(): void {
		$output = $this->block->render( array( 'title' => 'Hello' ) );
		$this->assertStringContainsString( 'Hello', $output );
		$this->assertStringContainsString( 'imm-banner-title', $output );
	}

	public function testRenderEachTemplate(): void {
		foreach ( array( 'overlay', 'card', 'side' ) as $template ) {
			$output = $this->block->render(
				array(
					'title'    => 'X',
					'template' => $template,
				)
			);
			$this->assertStringContainsString( "imm-block--banner--{$template}", $output );
		}
	}

	public function testRenderInvalidTemplateFallsBackToOverlay(): void {
		$output = $this->block->render(
			array(
				'title'    => 'X',
				'template' => 'bogus',
			)
		);
		$this->assertStringContainsString( 'imm-block--banner--overlay', $output );
	}

	public function testRenderMultiCta(): void {
		$output = $this->block->render(
			array(
				'title' => 'Header',
				'cta'   => array(
					array(
						'label'  => 'Buy',
						'url'    => 'https://example.com/buy',
						'target' => '_self',
						'style'  => 'primary',
					),
					array(
						'label'  => 'Learn',
						'url'    => 'https://example.com/learn',
						'target' => '_blank',
						'style'  => 'secondary',
					),
				),
			)
		);
		$this->assertStringContainsString( 'imm-banner-ctas', $output );
		$this->assertStringContainsString( 'https://example.com/buy', $output );
		$this->assertStringContainsString( 'https://example.com/learn', $output );
		$this->assertStringContainsString( 'target="_blank"', $output );
		$this->assertStringContainsString( 'rel="noopener noreferrer"', $output );
	}

	public function testRenderCtaEscapesUrl(): void {
		$output = $this->block->render(
			array(
				'cta' => array(
					array(
						'label' => 'X',
						'url'   => 'javascript:alert(1)',
					),
				),
			)
		);
		$this->assertStringNotContainsString( 'javascript:alert(1)', $output );
	}

	public function testRenderLinkWrapsBannerWhenNoCtas(): void {
		$output = $this->block->render(
			array(
				'title' => 'T',
				'link'  => 'https://example.com',
			)
		);
		$this->assertStringContainsString( 'imm-banner-link', $output );
	}

	public function testRenderLinkDoesNotWrapWhenCtasPresent(): void {
		$output = $this->block->render(
			array(
				'title' => 'T',
				'link'  => 'https://example.com',
				'cta'   => array(
					array(
						'label' => 'A',
						'url'   => 'https://example.com/a',
					),
				),
			)
		);
		$this->assertStringNotContainsString( 'imm-banner-link', $output );
	}

	public function testRenderOverlayOnlyOnOverlayTemplate(): void {
		$output = $this->block->render(
			array(
				'title'           => 'T',
				'image_id'        => 0,
				'template'        => 'card',
				'overlay_color'   => '#000000',
				'overlay_opacity' => 0.5,
			)
		);
		$this->assertStringNotContainsString( 'imm-banner__overlay', $output );
	}
}
