<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\PageCondition;
use PHPUnit\Framework\TestCase;

final class PageConditionTest extends TestCase
{
    private PageCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new PageCondition();
    }

    public function testType(): void
    {
        $this->assertSame('page', $this->condition->type());
    }

    public function testHasLabel(): void
    {
        $this->assertNotEmpty($this->condition->label());
    }

    public function testEmptyConfigDefaultsToNotMatched(): void
    {
        $this->assertFalse($this->condition->evaluate([]));
    }

    public function testEmptyConfigWithHideMode(): void
    {
        $this->assertTrue($this->condition->evaluate(['mode' => 'hide_on']));
    }
}
