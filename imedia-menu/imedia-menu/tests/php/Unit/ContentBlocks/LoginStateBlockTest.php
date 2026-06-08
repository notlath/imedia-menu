<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\LoginStateBlock;
use IMedia\Menu\ContentBlocks\Registry;
use PHPUnit\Framework\TestCase;

final class LoginStateBlockTest extends TestCase {

	private LoginStateBlock $block;
	private Registry $registry;

	protected function setUp(): void {
		$this->block    = new LoginStateBlock();
		$this->registry = new Registry();
		$this->block->setRegistry( $this->registry );
	}

	public function testType(): void {
		$this->assertSame( 'login_state', $this->block->type() );
	}

	public function testDefaultConfigShape(): void {
		$cfg = $this->block->defaultConfig();
		$this->assertArrayHasKey( 'logged_in_blocks', $cfg );
		$this->assertArrayHasKey( 'logged_out_blocks', $cfg );
		$this->assertArrayHasKey( 'fallback', $cfg );
		$this->assertSame( 'empty', $cfg['fallback'] );
	}

	public function testRenderEmptyBranchWithEmptyFallback(): void {
		$output = $this->block->render(
			array(
				'logged_in_blocks'  => array(),
				'logged_out_blocks' => array(),
			)
		);
		$this->assertStringContainsString( 'imm-block--login-state', $output );
		$this->assertStringContainsString( 'data-state="out"', $output );
	}

	public function testRenderEmptyBranchWithHideFallbackReturnsEmpty(): void {
		$output = $this->block->render(
			array(
				'logged_in_blocks'  => array(),
				'logged_out_blocks' => array(),
				'fallback'          => 'hide',
			)
		);
		$this->assertSame( '', $output );
	}

	public function testRenderRendersChildBlock(): void {
		$output = $this->block->render(
			array(
				'logged_out_blocks' => array(
					array(
						'type'   => 'text',
						'config' => array( 'content' => 'Sign in please' ),
					),
				),
			)
		);
		$this->assertStringContainsString( 'imm-block--text', $output );
		$this->assertStringContainsString( 'Sign in please', $output );
	}

	public function testRenderWithoutRegistryDoesNotError(): void {
		$fresh  = new LoginStateBlock();
		$output = $fresh->render(
			array(
				'logged_in_blocks' => array(
					array(
						'type'   => 'text',
						'config' => array( 'content' => 'X' ),
					),
				),
			)
		);
		$this->assertStringContainsString( 'imm-block--login-state', $output );
	}
}
