<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\DividerBlock;
use PHPUnit\Framework\TestCase;

final class DividerBlockTest extends TestCase
{
    private DividerBlock $block;

    protected function setUp(): void
    {
        $this->block = new DividerBlock();
    }

    public function testType(): void
    {
        $this->assertSame('divider', $this->block->type());
    }

    public function testTitle(): void
    {
        $this->assertNotEmpty($this->block->title());
    }

    public function testRender(): void
    {
        $output = $this->block->render([]);
        $this->assertStringContainsString('imm-block--divider', $output);
    }

    public function testRenderWithHeight(): void
    {
        $output = $this->block->render(['height' => '10px']);
        $this->assertStringContainsString('10px', $output);
    }

    public function testRenderWithStyle(): void
    {
        $output = $this->block->render(['style' => 'dashed']);
        $this->assertStringContainsString('dashed', $output);
    }

    public function testDefaultConfig(): void
    {
        $config = $this->block->defaultConfig();
        $this->assertArrayHasKey('height', $config);
        $this->assertArrayHasKey('style', $config);
        $this->assertArrayHasKey('color', $config);
        $this->assertArrayHasKey('margin', $config);
    }
}
