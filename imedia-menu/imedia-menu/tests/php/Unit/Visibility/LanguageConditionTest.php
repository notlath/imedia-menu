<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\LanguageCondition;
use PHPUnit\Framework\TestCase;

final class LanguageConditionTest extends TestCase
{
    private LanguageCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new LanguageCondition();
    }

    public function testType(): void
    {
        $this->assertSame('language', $this->condition->type());
    }

    public function testNoLocalesReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate(['locales' => []]));
    }

    public function testNoConfigReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([]));
    }

    public function testRespectsLocaleDetectionMethodWpml(): void
    {
        $settings = $GLOBALS['_imedia_menu_options'] ?? array();
        $settings['locale_detection_method'] = 'wpml';
        $GLOBALS['_imedia_menu_options'] = $settings;
        $this->assertFalse($this->condition->evaluate(['locales' => ['de_DE']]));
    }

    public function testRespectsLocaleDetectionMethodPolylang(): void
    {
        $settings = $GLOBALS['_imedia_menu_options'] ?? array();
        $settings['locale_detection_method'] = 'polylang';
        $GLOBALS['_imedia_menu_options'] = $settings;
        $this->assertFalse($this->condition->evaluate(['locales' => ['de_DE']]));
    }

    public function testRespectsLocaleDetectionMethodTranslatePress(): void
    {
        $settings = $GLOBALS['_imedia_menu_options'] ?? array();
        $settings['locale_detection_method'] = 'translatepress';
        $GLOBALS['_imedia_menu_options'] = $settings;
        $this->assertFalse($this->condition->evaluate(['locales' => ['de_DE']]));
    }
}
