<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class GutenbergBlock implements ContentBlock
{
    public function type(): string
    {
        return 'gutenberg_block';
    }

    public function title(): string
    {
        return __('Gutenberg Block', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $blockName = $config['block_name'] ?? '';
        $blockAttrs = $config['block_attrs'] ?? [];

        if (empty($blockName)) {
            return sprintf(
                '<div class="imm-block imm-block--gutenberg"><p class="imm-empty">%s</p></div>',
                esc_html__('Select a Gutenberg block', 'imedia-menu')
            );
        }

        $rendered = render_block([
            'blockName'    => $blockName,
            'attrs'        => $blockAttrs,
            'innerContent' => $config['inner_content'] ?? [],
        ]);

        return sprintf(
            '<div class="imm-block imm-block--gutenberg">%s</div>',
            $rendered
        );
    }

    public function defaultConfig(): array
    {
        return [
            'block_name'    => '',
            'block_attrs'   => [],
            'inner_content' => [],
        ];
    }
}
