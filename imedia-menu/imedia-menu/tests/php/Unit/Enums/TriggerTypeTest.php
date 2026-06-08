<?php

declare(strict_types=1);

namespace IMedia\Menu\Tests\Unit\Enums;

use IMedia\Menu\Enums\TriggerType;
use PHPUnit\Framework\TestCase;

final class TriggerTypeTest extends TestCase
{
    public function testEnumCasesExist(): void
    {
        $this->assertTrue(TriggerType::tryFrom('hover') !== null);
        $this->assertTrue(TriggerType::tryFrom('click') !== null);
    }

    public function testInvalidCaseReturnsNull(): void
    {
        $this->assertNull(TriggerType::tryFrom('scroll'));
    }

    public function testLabelReturnsNonEmpty(): void
    {
        $cases = TriggerType::cases();
        foreach ($cases as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function testValuesUnique(): void
    {
        $values = array_map(fn (TriggerType $t) => $t->value, TriggerType::cases());
        $this->assertCount(count($values), array_unique($values));
    }
}
