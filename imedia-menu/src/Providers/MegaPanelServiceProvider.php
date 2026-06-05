<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;

final class MegaPanelServiceProvider implements ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueBuilderAssets']);
    }

    public function enqueueBuilderAssets(string $hook): void
    {
        if ($hook !== 'appearance_page_imedia-menu') {
            return;
        }

        $screen = get_current_screen();
        if ($screen && isset($_GET['tab']) && $_GET['tab'] !== 'builder') { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        wp_enqueue_media();
        wp_enqueue_editor();
    }
}
