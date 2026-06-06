<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Contracts;

interface SettingsTab {

	public function id(): string;

	public function label(): string;

	public function render( array $settings ): void;

	public function validate( array $input ): array;

	public function sanitize( array $input ): array;
}
