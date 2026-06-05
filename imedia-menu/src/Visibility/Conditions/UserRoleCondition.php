<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class UserRoleCondition implements VisibilityCondition
{
    public function type(): string
    {
        return 'user_role';
    }

    public function label(): string
    {
        return __('User Roles', 'imedia-menu');
    }

    public function evaluate(array $config): bool
    {
        $allowedRoles = $config['roles'] ?? [];
        $mode         = $config['mode'] ?? 'any';

        if (empty($allowedRoles)) {
            return true;
        }

        if (!is_user_logged_in()) {
            return false;
        }

        $user = wp_get_current_user();
        $userRoles = $user->roles;

        $matched = !empty(array_intersect($allowedRoles, $userRoles));

        return $mode === 'any' ? $matched : !$matched;
    }
}
