<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\IconBlock;
use PHPUnit\Framework\TestCase;

final class IconBlockTest extends TestCase
{
    private IconBlock $block;

    protected function setUp(): void
    {
        $this->block = new IconBlock();
    }

    public function testType(): void
    {
        $this->assertSame('icon', $this->block->type());
    }

    public function testRenderWithIcon(): void
    {
        $output = $this->block->render(['icon' => 'dashicons:star-filled', 'provider' => 'dashicons']);
        $this->assertNotEmpty($output);
    }

    public function testRenderEmpty(): void
    {
        $output = $this->block->render([]);
        $this->assertStringContainsString('imm-block--icon', $output);
    }
}
