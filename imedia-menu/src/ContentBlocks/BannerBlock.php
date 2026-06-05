<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class BannerBlock implements ContentBlock
{
    public function type(): string
    {
        return 'banner';
    }

    public function title(): string
    {
        return __('Banner / CTA', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $imageId    = (int) ($config['image_id'] ?? 0);
        $title      = $config['title'] ?? '';
        $text       = $config['text'] ?? '';
        $link       = $config['link'] ?? '';
        $buttonText = $config['button_text'] ?? '';
        $alt        = $config['alt'] ?? '';

        $styleAttr = '';
        if (!empty($styles)) {
            $css = [];
            if (isset($styles['borderRadius'])) {
                $css[] = 'border-radius:' . $styles['borderRadius'];
            }
            if (!empty($css)) {
                $styleAttr = ' style="' . esc_attr(implode(';', $css)) . '"';
            }
        }

        $html = sprintf(
            '<div class="imm-block imm-block--banner"%s>',
            $styleAttr
        );

        if ($link) {
            $html .= sprintf('<a href="%s" class="imm-banner-link">', esc_url($link));
        }

        if ($imageId) {
            $html .= wp_get_attachment_image($imageId, 'large', false, [
                'class' => 'imm-banner-image',
                'alt'   => $alt ?: '',
                'loading' => 'lazy',
            ]);
        }

        if ($title || $text || $buttonText) {
            $html .= '<div class="imm-banner-content">';

            if ($title) {
                $html .= sprintf(
                    '<h4 class="imm-banner-title">%s</h4>',
                    esc_html($title)
                );
            }

            if ($text) {
                $html .= sprintf(
                    '<p class="imm-banner-text">%s</p>',
                    esc_html($text)
                );
            }

            if ($buttonText) {
                $html .= sprintf(
                    '<span class="imm-banner-button">%s</span>',
                    esc_html($buttonText)
                );
            }

            $html .= '</div>';
        }

        if ($link) {
            $html .= '</a>';
        }

        $html .= '</div>';

        return $html;
    }

    public function defaultConfig(): array
    {
        return [
            'image_id'   => 0,
            'title'      => '',
            'text'       => '',
            'link'       => '',
            'button_text' => '',
            'alt'        => '',
        ];
    }
}
