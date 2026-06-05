<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\ContentBlocks;

use IMedia\Menu\ContentBlocks\Registry;
use PHPUnit\Framework\TestCase;

final class BlockRegistryTest extends TestCase
{
    private Registry $registry;

    protected function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function testHasAllBlockTypes(): void
    {
        $types = [
            'menu_links',
            'heading',
            'text',
            'icon',
            'image',
            'banner',
            'gutenberg_block',
            'widget',
            'html',
            'shortcode',
            'post_listing',
            'taxonomy_listing',
            'search',
            'divider',
        ];
        $all = $this->registry->getAll();

        foreach ($types as $type) {
            $this->assertArrayHasKey($type, $all, "Block type '$type' not registered");
        }
    }

    public function testGetReturnsNullForUnknown(): void
    {
        $this->assertNull($this->registry->get('nonexistent'));
    }

    public function testAllBlockTypesImplementInterface(): void
    {
        foreach ($this->registry->getAll() as $type => $block) {
            $this->assertInstanceOf(
                \IMedia\Menu\Contracts\ContentBlock::class,
                $block,
                "Block type '$type' does not implement ContentBlock"
            );
        }
    }

    public function testCount(): void
    {
        $this->assertCount(14, $this->registry->getAll());
    }
}
