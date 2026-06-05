<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Icons\IconManager;
use IMedia\Menu\Icons\Providers\DashiconsProvider;
use IMedia\Menu\Icons\Providers\FontAwesomeProvider;
use IMedia\Menu\Icons\Providers\CustomSvgProvider;

final class IconServiceProvider implements ServiceProvider
{
    private IconManager $manager;

    public function register(): void
    {
        $this->manager = new IconManager();
    }

    public function boot(): void
    {
        $settings = get_option('imedia_menu_settings', []);
        $enabledProviders = $settings['icon_providers'] ?? ['dashicons'];

        if (in_array('dashicons', $enabledProviders, true)) {
            $this->manager->register(new DashiconsProvider());
        }

        if (in_array('fontawesome', $enabledProviders, true)) {
            $this->manager->register(new FontAwesomeProvider());
        }

        if (in_array('custom_svg', $enabledProviders, true)) {
            $this->manager->register(new CustomSvgProvider());
        }
    }

    public function getManager(): IconManager
    {
        return $this->manager;
    }
}
