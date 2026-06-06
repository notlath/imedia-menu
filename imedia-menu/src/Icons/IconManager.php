<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons;

use IMedia\Menu\Contracts\IconProvider;
use IMedia\Menu\Icons\IconProviderRegistry;

final class IconManager {

	private IconProviderRegistry $registry;

	public function __construct() {
		$this->registry = new IconProviderRegistry();
	}

	public function register( IconProvider $provider ): void {
		$this->registry->register( $provider );
	}

	public function getIcon( string $iconIdentifier ): string {
		$parts      = explode( ':', $iconIdentifier, 2 );
		$providerId = $parts[0] ?? '';
		$iconId     = $parts[1] ?? '';

		$provider = $this->registry->get( $providerId );

		if ( $provider === null ) {
			return '';
		}

		return $provider->getIcon( $iconId );
	}

	public function getAvailableIcons(): array {
		$result = array();

		foreach ( $this->registry->getAll() as $provider ) {
			$result[ $provider->id() ] = array(
				'name'  => $provider->name(),
				'icons' => $provider->getAvailableIcons(),
			);
		}

		return $result;
	}

	public function getRegistry(): IconProviderRegistry {
		return $this->registry;
	}
}
