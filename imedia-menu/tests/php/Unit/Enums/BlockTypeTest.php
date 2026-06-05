<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\BlockType;
use PHPUnit\Framework\TestCase;

final class BlockTypeTest extends TestCase
{
    public function testAllBlockTypesExist(): void
    {
        $values = array_map(fn (BlockType $t) => $t->value, BlockType::cases());
        $this->assertContains('menu_links', $values);
        $this->assertContains('heading', $values);
        $this->assertContains('text', $values);
        $this->assertContains('icon', $values);
        $this->assertContains('image', $values);
        $this->assertContains('banner', $values);
        $this->assertContains('gutenberg_block', $values);
        $this->assertContains('widget', $values);
        $this->assertContains('html', $values);
        $this->assertContains('shortcode', $values);
        $this->assertContains('post_listing', $values);
        $this->assertContains('taxonomy_listing', $values);
        $this->assertContains('search', $values);
        $this->assertContains('divider', $values);
    }

    public function testAllCasesHaveLabels(): void
    {
        foreach (BlockType::cases() as $type) {
            $this->assertNotEmpty($type->label());
        }
    }

    public function testCount(): void
    {
        $this->assertCount(14, BlockType::cases());
    }

    public function testInvalidValue(): void
    {
        $this->assertNull(BlockType::tryFrom('nonexistent'));
    }
}
