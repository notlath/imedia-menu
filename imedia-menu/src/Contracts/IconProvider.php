<?php

declare(strict_types=1);

namespace IMedia\Menu\Contracts;

interface IconProvider {

	public function id(): string;

	public function name(): string;

	public function getIcon( string $identifier ): string;

	public function getAvailableIcons(): array;
}
