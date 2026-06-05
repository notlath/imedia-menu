<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Frontend\MobileNav;

final class MobileServiceProvider implements ServiceProvider
{
    private MobileNav $mobileNav;

    public function register(): void
    {
        $this->mobileNav = new MobileNav();
    }

    public function boot(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueMobileAssets'], 110);
        add_action('wp_footer', [$this->mobileNav, 'renderOffCanvas']);
    }

    public function enqueueMobileAssets(): void
    {
        $settings = get_option('imedia_menu_settings', []);
        $bp       = (int) ($settings['mobile_breakpoint'] ?? 768);

        wp_enqueue_style(
            'imm-mobile',
            URL . 'assets/frontend/css/imm-mobile.css',
            ['imm-base'],
            VERSION,
            sprintf('(max-width: %dpx)', $bp)
        );

        wp_enqueue_script(
            'imm-mobile',
            URL . 'assets/frontend/js/imm-mobile.js',
            ['imm'],
            VERSION,
            true
        );

        wp_localize_script('imm-mobile', 'immMobileData', [
            'breakpoint'  => $bp,
            'direction'   => $settings['off_canvas_direction'] ?? 'right',
            'hamburger'   => $settings['hamburger_style'] ?? 'classic',
        ]);
    }
}
