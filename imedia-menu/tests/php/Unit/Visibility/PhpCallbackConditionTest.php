<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\PhpCallbackCondition;
use PHPUnit\Framework\TestCase;

final class PhpCallbackConditionTest extends TestCase
{
    private PhpCallbackCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new PhpCallbackCondition();
    }

    public function testType(): void
    {
        $this->assertSame('php_callback', $this->condition->type());
    }

    public function testEmptyCallbackReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate(['callback' => '']));
    }

    public function testEmptyConfigReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate([]));
    }
}
