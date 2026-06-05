<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class TextBlock implements ContentBlock
{
    public function type(): string
    {
        return 'text';
    }

    public function title(): string
    {
        return __('Text / Rich Text', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $content = $config['content'] ?? '';

        $styleAttr = '';
        if (!empty($styles)) {
            $css = [];
            if (isset($styles['fontSize'])) {
                $css[] = 'font-size:' . $styles['fontSize'];
            }
            if (isset($styles['color'])) {
                $css[] = 'color:' . $styles['color'];
            }
            if (!empty($css)) {
                $styleAttr = ' style="' . esc_attr(implode(';', $css)) . '"';
            }
        }

        return sprintf(
            '<div class="imm-block imm-block--text"%s>%s</div>',
            $styleAttr,
            wp_kses_post($content)
        );
    }

    public function defaultConfig(): array
    {
        return [
            'content' => '',
        ];
    }
}
