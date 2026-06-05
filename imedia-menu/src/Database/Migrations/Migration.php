<?php

declare(strict_types=1);

namespace IMedia\Menu\Database\Migrations;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Migration
{
    public function __construct(
        public readonly string $version,
        public readonly string $description = '',
    ) {}
}
