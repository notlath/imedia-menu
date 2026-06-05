<?php

declare(strict_types=1);

namespace IMedia\Menu\Contracts;

interface VisibilityCondition
{
    public function type(): string;

    public function label(): string;

    public function evaluate(array $config): bool;
}
