<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class MenuLinksBlock implements ContentBlock
{
    public function type(): string
    {
        return 'menu_links';
    }

    public function title(): string
    {
        return __('Menu Links', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $showDescriptions = $config['show_descriptions'] ?? false;
        $showIcons        = $config['show_icons'] ?? false;
        $source           = $config['source'] ?? 'children';

        if ($source === 'children') {
            return $this->renderPlaceholder($showDescriptions, $showIcons);
        }

        return $this->renderPlaceholder($showDescriptions, $showIcons);
    }

    public function defaultConfig(): array
    {
        return [
            'source'            => 'children',
            'show_descriptions' => false,
            'show_icons'        => true,
            'max_depth'         => 0,
        ];
    }

    private function renderPlaceholder(bool $showDescriptions, bool $showIcons): string
    {
        $html = '<ul class="imm-block imm-block--links" role="menu">';

        $html .= '<li role="none">';
        $html .= '<a href="#" role="menuitem" class="imm-link">';

        if ($showIcons) {
            $html .= '<span class="imm-link-icon dashicons dashicons-admin-links" aria-hidden="true"></span>';
        }

        $html .= '<span class="imm-link-text">';
        $html .= '<span class="imm-link-label">' . esc_html__('Link Item', 'imedia-menu') . '</span>';

        if ($showDescriptions) {
            $html .= '<span class="imm-link-desc">' . esc_html__('Link description', 'imedia-menu') . '</span>';
        }

        $html .= '</span></a></li>';

        $html .= '<li role="none">';
        $html .= '<a href="#" role="menuitem" class="imm-link">';
        $html .= '<span class="imm-link-text">';
        $html .= '<span class="imm-link-label">' . esc_html__('Another Link', 'imedia-menu') . '</span>';
        $html .= '</span></a></li>';

        $html .= '</ul>';

        return $html;
    }
}
