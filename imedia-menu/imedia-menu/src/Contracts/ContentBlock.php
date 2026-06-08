<?php

declare(strict_types=1);

namespace IMedia\Menu\Contracts;

interface ContentBlock {

	public function type(): string;

	public function title(): string;

	public function render( array $config, array $styles = array() ): string;

	public function defaultConfig(): array;
}
