<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class ToggleBlockRegistry {

	/**
	 * Registered toggle blocks keyed by type.
	 *
	 * @var array<string, ToggleBlock>
	 */
	private array $blocks = array();

	public function register( ToggleBlock $block ): void {
		$this->blocks[ $block->type() ] = $block;
	}

	public function get( string $type ): ?ToggleBlock {
		return $this->blocks[ $type ] ?? null;
	}

	public function all(): array {
		return $this->blocks;
	}

	public function has( string $type ): bool {
		return isset( $this->blocks[ $type ] );
	}

	public function getTypes(): array {
		return array_keys( $this->blocks );
	}

	public function getLabels(): array {
		$labels = array();
		foreach ( $this->blocks as $type => $block ) {
			$labels[ $type ] = $block->label();
		}
		return $labels;
	}
}
