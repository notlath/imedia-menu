<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Visibility\ConditionRegistry;

final class VisibilityServiceProvider implements ServiceProvider
{
    private ConditionRegistry $registry;

    public function register(): void
    {
        $this->registry = new ConditionRegistry();
    }

    public function boot(): void
    {
        add_filter('imedia_menu_visibility_conditions', function (array $conditions): array {
            return array_merge($conditions, $this->registry->getConditions());
        });
    }

    public function getRegistry(): ConditionRegistry
    {
        return $this->registry;
    }
}
