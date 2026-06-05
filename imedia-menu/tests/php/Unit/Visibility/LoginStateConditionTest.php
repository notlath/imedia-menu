<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\LoginStateCondition;
use PHPUnit\Framework\TestCase;

final class LoginStateConditionTest extends TestCase
{
    private LoginStateCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new LoginStateCondition();
    }

    public function testReturnsCorrectType(): void
    {
        $this->assertSame('login_state', $this->condition->type());
    }

    public function testHasLabel(): void
    {
        $this->assertNotEmpty($this->condition->label());
    }

    public function testLoggedOutIsNotLoggedIn(): void
    {
        $this->assertFalse($this->condition->evaluate(['state' => 'logged_in']));
    }

    public function testLoggedOutAllowsLoggedOut(): void
    {
        $this->assertTrue($this->condition->evaluate(['state' => 'logged_out']));
    }

    public function testUnknownStateReturnsTrue(): void
    {
        $this->assertTrue($this->condition->evaluate(['state' => 'unknown']));
    }

    public function testDefaultStateIsLoggedIn(): void
    {
        $this->assertFalse($this->condition->evaluate([]));
    }
}
