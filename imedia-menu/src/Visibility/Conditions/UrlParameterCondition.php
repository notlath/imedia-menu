<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class UrlParameterCondition implements VisibilityCondition
{
    public function type(): string
    {
        return 'url_parameter';
    }

    public function label(): string
    {
        return __('URL Parameters', 'imedia-menu');
    }

    public function evaluate(array $config): bool
    {
        $params    = $config['params'] ?? [];
        $mode      = $config['mode'] ?? 'any';

        if (empty($params)) {
            return true;
        }

        $matched = false;

        foreach ($params as $param) {
            $key       = $param['key'] ?? '';
            $value     = $param['value'] ?? '';
            $operator  = $param['operator'] ?? 'equals';

            if (empty($key)) {
                continue;
            }

            $actualValue = sanitize_text_field(wp_unslash($_GET[$key] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

            $paramMatched = match ($operator) {
                'equals'     => $actualValue === $value,
                'not_equals' => $actualValue !== $value,
                'exists'     => $actualValue !== '',
                'not_exists' => $actualValue === '',
                'contains'   => str_contains($actualValue, $value),
                'regex'      => (bool) preg_match($value, $actualValue),
                default      => $actualValue === $value,
            };

            if ($paramMatched) {
                $matched = true;
                if ($mode === 'any') {
                    return true;
                }
            } else {
                if ($mode === 'all') {
                    return false;
                }
            }
        }

        return $matched;
    }
}
