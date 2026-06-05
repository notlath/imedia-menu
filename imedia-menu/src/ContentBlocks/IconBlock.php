<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class IconBlock implements ContentBlock
{
    public function type(): string
    {
        return 'icon';
    }

    public function title(): string
    {
        return __('Icon', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $icon    = $config['icon'] ?? 'dashicons:admin-home';
        $size    = $config['size'] ?? '24px';
        $color   = $config['color'] ?? '';
        $align   = $config['align'] ?? 'left';
        $link    = $config['link'] ?? '';

        $parts  = explode(':', $icon, 2);
        $iconClass = '';

        if (($parts[0] ?? '') === 'dashicons') {
            $iconClass = 'dashicons dashicons-' . ($parts[1] ?? 'admin-home');
        }

        $style = 'font-size:' . $size;
        if ($color) {
            $style .= ';color:' . $color;
        }
        if (!empty($styles)) {
            if (isset($styles['margin'])) {
                $style .= ';margin:' . $styles['margin'];
            }
        }

        $html = sprintf(
            '<span class="imm-block imm-block--icon imm-icon--align-%s" style="text-align:%s">',
            esc_attr($align),
            esc_attr($align)
        );

        $iconHtml = sprintf(
            '<span class="%s" style="%s" aria-hidden="true"></span>',
            esc_attr($iconClass),
            esc_attr($style)
        );

        if ($link) {
            $html .= sprintf(
                '<a href="%s">%s</a>',
                esc_url($link),
                $iconHtml
            );
        } else {
            $html .= $iconHtml;
        }

        $html .= '</span>';

        return $html;
    }

    public function defaultConfig(): array
    {
        return [
            'icon'  => 'dashicons:admin-home',
            'size'  => '24px',
            'color' => '',
            'align' => 'left',
            'link'  => '',
        ];
    }
}
