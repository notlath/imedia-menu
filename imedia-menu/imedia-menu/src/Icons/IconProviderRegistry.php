<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons;

use IMedia\Menu\Contracts\IconProvider;

final class IconProviderRegistry {

	/** @var array<string, IconProvider> */
	private array $providers = array();

	public function register( IconProvider $provider ): void {
		$this->providers[ $provider->id() ] = $provider;
	}

	public function get( string $id ): ?IconProvider {
		return $this->providers[ $id ] ?? null;
	}

	public function getAll(): array {
		return $this->providers;
	}
}
