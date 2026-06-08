<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Visibility;

use IMedia\Menu\Visibility\Conditions\UserRoleCondition;
use PHPUnit\Framework\TestCase;

final class UserRoleConditionTest extends TestCase
{
    private UserRoleCondition $condition;

    protected function setUp(): void
    {
        $this->condition = new UserRoleCondition();
    }

    public function testType(): void
    {
        $this->assertSame('user_role', $this->condition->type());
    }

    public function testReturnsTrueWhenNoRolesConfigured(): void
    {
        $this->assertTrue($this->condition->evaluate(['roles' => []]));
    }

    public function testReturnsTrueWhenEmptyConfig(): void
    {
        $this->assertTrue($this->condition->evaluate([]));
    }
}
