<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class LanguageCondition implements VisibilityCondition
{
    public function type(): string
    {
        return 'language';
    }

    public function label(): string
    {
        return __('Language/Locale', 'imedia-menu');
    }

    public function evaluate(array $config): bool
    {
        $allowedLocales = $config['locales'] ?? [];

        if (empty($allowedLocales)) {
            return true;
        }

        $currentLocale = $this->detectLocale();

        return in_array($currentLocale, $allowedLocales, true);
    }

    private function detectLocale(): string
    {
        if (function_exists('pll_current_language')) {
            return pll_current_language('locale');
        }

        if (defined('ICL_LANGUAGE_CODE') && function_exists('wpml_get_language_information')) {
            global $post;
            if ($post) {
                $info = wpml_get_language_information($post);
                return $info['locale'] ?? get_locale();
            }
        }

        if (function_exists('trp_get_language')) {
            $lang = trp_get_language();
            if ($lang) {
                return $lang;
            }
        }

        return get_locale();
    }
}
