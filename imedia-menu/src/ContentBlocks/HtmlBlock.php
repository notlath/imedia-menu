<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class HtmlBlock implements ContentBlock
{
    public function type(): string
    {
        return 'html';
    }

    public function title(): string
    {
        return __('Custom HTML', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $html = $config['html'] ?? '';

        $allowedHtml = wp_kses_allowed_html('post');

        $allowedHtml['script'] = [
            'type'     => true,
            'src'      => true,
            'async'    => true,
            'defer'    => true,
        ];

        $allowedHtml['style'] = [
            'type' => true,
            'media' => true,
        ];

        $sanitized = wp_kses($html, $allowedHtml);

        return sprintf(
            '<div class="imm-block imm-block--html">%s</div>',
            $sanitized
        );
    }

    public function defaultConfig(): array
    {
        return [
            'html' => '',
        ];
    }
}
