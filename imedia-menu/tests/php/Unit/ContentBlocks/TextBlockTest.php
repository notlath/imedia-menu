<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\TextBlock;
use PHPUnit\Framework\TestCase;

final class TextBlockTest extends TestCase
{
    private TextBlock $block;

    protected function setUp(): void
    {
        $this->block = new TextBlock();
    }

    public function testType(): void
    {
        $this->assertSame('text', $this->block->type());
    }

    public function testRenderWithContent(): void
    {
        $output = $this->block->render(['content' => '<p>Hello</p>']);
        $this->assertStringContainsString('Hello', $output);
    }

    public function testRenderEmpty(): void
    {
        $output = $this->block->render([]);
        $this->assertStringContainsString('imm-block--text', $output);
    }
}
