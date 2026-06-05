<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;

final class BlockEditorServiceProvider implements ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        add_action('init', [$this, 'registerBlock']);
    }

    public function registerBlock(): void
    {
        register_block_type(DIR . '/assets/blocks/navigation-block', [
            'render_callback' => [$this, 'renderNavigationBlock'],
        ]);
    }

    public function renderNavigationBlock(array $attributes, string $content): string
    {
        $menuId = $attributes['menuId'] ?? 0;
        $className = $attributes['className'] ?? '';

        if ($menuId === 0) {
            return sprintf(
                '<nav class="%s"><p>%s</p></nav>',
                esc_attr($className),
                esc_html__('Select a menu in the block settings.', 'imedia-menu')
            );
        }

        $menu = wp_get_nav_menu_object($menuId);

        if (!$menu) {
            return sprintf(
                '<nav class="%s"><p>%s</p></nav>',
                esc_attr($className),
                esc_html__('Menu not found.', 'imedia-menu')
            );
        }

        $args = [
            'menu'           => $menuId,
            'menu_class'     => 'imm-menu',
            'container'      => 'nav',
            'container_class' => 'imm-nav ' . $className,
            'container_aria_label' => $menu->name,
            'fallback_cb'    => false,
            'walker'         => new \IMedia\Menu\Frontend\MenuWalker($menuId),
        ];

        add_filter('wp_nav_menu_items', [$this, 'maybePrependMobileToggle'], 10, 2);

        ob_start();
        wp_nav_menu($args);
        $menuHtml = ob_get_clean();

        remove_filter('wp_nav_menu_items', [$this, 'maybePrependMobileToggle'], 10);

        $menuHtml = $this->wrapMenu($menuHtml, $menuId, $menu->name);

        return $menuHtml;
    }

    public function maybePrependMobileToggle(string $items, object $args): string
    {
        if (str_contains($args->container_class ?? '', 'imm-nav')) {
            $toggle = sprintf(
                '<button class="imm-mobile-toggle" aria-expanded="false" aria-controls="imm-menu-%d" aria-label="%s">
                    <span class="imm-hamburger"><span></span><span></span><span></span></span>
                </button>',
                $args->menu->term_id ?? 0,
                esc_attr__('Toggle navigation menu', 'imedia-menu')
            );
            $items = $toggle . $items;
        }

        return $items;
    }

    private function wrapMenu(string $menuHtml, int $menuId, string $menuName): string
    {
        $settings = get_option('imedia_menu_settings', []);
        $trigger  = $settings['trigger_type'] ?? 'hover';
        $delay    = (int) ($settings['hover_delay'] ?? 200);

        $style = '';
        $cssVars = [];

        if (!empty($settings['menu_bar_bg'])) {
            $cssVars[] = '--imm-bg:' . $settings['menu_bar_bg'];
        }
        if (!empty($settings['menu_text_color'])) {
            $cssVars[] = '--imm-text:' . $settings['menu_text_color'];
        }

        if (!empty($cssVars)) {
            $style = ' style="' . esc_attr(implode(';', $cssVars)) . '"';
        }

        $search = '<nav';
        $replace = sprintf(
            '<nav data-trigger="%s" data-hover-delay="%d"%s',
            esc_attr($trigger),
            $delay,
            $style
        );

        $menuHtml = str_replace($search, $replace, $menuHtml);

        return $menuHtml;
    }
}
