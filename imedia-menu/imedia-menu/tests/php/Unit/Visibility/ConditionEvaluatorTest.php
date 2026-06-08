<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\ConditionEvaluator;
use PHPUnit\Framework\TestCase;

final class ConditionEvaluatorTest extends TestCase
{
    private ConditionEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new ConditionEvaluator();
    }

    public function testHasDefaultConditions(): void
    {
        $conditions = $this->evaluator->getAll();
        $this->assertCount(8, $conditions);
    }

    public function testGetAllReturnsKeyedByType(): void
    {
        $conditions = $this->evaluator->getAll();
        $this->assertArrayHasKey('login_state', $conditions);
        $this->assertArrayHasKey('user_role', $conditions);
        $this->assertArrayHasKey('device_type', $conditions);
        $this->assertArrayHasKey('page', $conditions);
        $this->assertArrayHasKey('schedule', $conditions);
        $this->assertArrayHasKey('language', $conditions);
        $this->assertArrayHasKey('url_parameter', $conditions);
        $this->assertArrayHasKey('php_callback', $conditions);
    }

    public function testGetReturnsNullForUnknown(): void
    {
        $this->assertNull($this->evaluator->get('nonexistent'));
    }

    public function testGetReturnsConditionForKnown(): void
    {
        $this->assertNotNull($this->evaluator->get('login_state'));
    }
}
