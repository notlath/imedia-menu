<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class ImageBlock implements ContentBlock
{
    public function type(): string
    {
        return 'image';
    }

    public function title(): string
    {
        return __('Image', 'imedia-menu');
    }

    public function render(array $config, array $styles = []): string
    {
        $imageId  = (int) ($config['image_id'] ?? 0);
        $alt      = $config['alt'] ?? '';
        $caption  = $config['caption'] ?? '';
        $link     = $config['link'] ?? '';

        if ($imageId) {
            $imageHtml = wp_get_attachment_image($imageId, 'medium', false, [
                'alt'   => $alt ?: '',
                'class' => 'imm-block-image-img',
                'loading' => 'lazy',
            ]);
        } else {
            $imageHtml = sprintf(
                '<div class="imm-block-image-placeholder">%s</div>',
                esc_html__('Select an image', 'imedia-menu')
            );
        }

        if (!$imageHtml) {
            return '';
        }

        $html = '<div class="imm-block imm-block--image">';

        if ($link) {
            $html .= sprintf('<a href="%s">', esc_url($link));
        }

        $html .= $imageHtml;

        if ($link) {
            $html .= '</a>';
        }

        if ($caption) {
            $html .= sprintf(
                '<span class="imm-block-image-caption">%s</span>',
                esc_html($caption)
            );
        }

        $html .= '</div>';

        return $html;
    }

    public function defaultConfig(): array
    {
        return [
            'image_id' => 0,
            'alt'      => '',
            'caption'  => '',
            'link'     => '',
        ];
    }
}
