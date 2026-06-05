<?php

declare(strict_types=1);

namespace IMedia\Menu\Contracts;

interface ServiceProvider
{
    public function register(): void;

    public function boot(): void;
}
