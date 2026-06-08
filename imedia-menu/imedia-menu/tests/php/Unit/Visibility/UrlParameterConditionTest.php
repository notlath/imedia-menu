<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\UrlParameterCondition;
use PHPUnit\Framework\TestCase;

final class UrlParameterConditionTest extends TestCase
{
    private UrlParameterCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new UrlParameterCondition();
    }

    public function testType(): void
    {
        $this->assertSame('url_parameter', $this->condition->type());
    }

    public function testEmptyParamsReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate(['params' => []]));
    }

    public function testEmptyConfigReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([]));
    }
}
