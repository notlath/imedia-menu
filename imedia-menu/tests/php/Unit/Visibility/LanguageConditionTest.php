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
}
