<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar\Contracts;

interface ToggleBlock {

	public function type(): string;

	public function label(): string;

	public function defaultSettings(): array;

	public function validate( array $settings ): array;

	public function render( array $settings, array $args ): string;

	public function requiredStylesheet(): ?string;

	public function requiredScript(): ?string;
}
