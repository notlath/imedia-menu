<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\ToggleBar;

use IMedia\Menu\Frontend\ToggleBar\Contracts\ToggleBlock;

final class ToggleBarRenderer {

	/**
	 * Toggle bar storage repository.
	 *
	 * @var ToggleBarRepository
	 */
	private ToggleBarRepository $repository;

	/**
	 * Registered toggle block implementations.
	 *
	 * @var ToggleBlockRegistry
	 */
	private ToggleBlockRegistry $registry;

	public function __construct( ?ToggleBarRepository $repository = null, ?ToggleBlockRegistry $registry = null ) {
		$this->repository = $repository ?? new ToggleBarRepository();
		$this->registry   = $registry ?? $this->createDefaultRegistry();
	}

	public function render( string $location, array $args = array() ): string {
		$blocks = $this->repository->get( $location );

		if ( empty( $blocks ) ) {
			return '';
		}

		$regions = $this->sortByRegion( $blocks );
		$html    = $this->renderBar( $location, $regions, $args );

		/**
		 * Filter the final toggle bar HTML.
		 *
		 * @since 1.3.0
		 *
		 * @param string $html     The toggle bar HTML.
		 * @param string $location The menu location slug.
		 * @param array  $blocks   The raw block data.
		 */
		return apply_filters( 'imm_toggle_bar_html', $html, $location, $blocks );
	}

	public function renderForAllLocations( array $args = array() ): string {
		$all    = $this->repository->getAll();
		$output = '';
		foreach ( array_keys( $all ) as $location ) {
			$output .= $this->render( $location, $args );
		}
		return $output;
	}

	public function hasBlocksForLocation( string $location ): bool {
		return $this->repository->hasBlocks( $location );
	}

	public function hasAnyBlocks(): bool {
		return $this->repository->anyLocationHasBlocks();
	}

	private function sortByRegion( array $blocks ): array {
		$regions = array(
			'left'   => array(),
			'center' => array(),
			'right'  => array(),
		);

		foreach ( $blocks as $block ) {
			$align = $block['align'] ?? 'left';
			if ( ! isset( $regions[ $align ] ) ) {
				$align = 'left';
			}
			$regions[ $align ][] = $block;
		}

		return $regions;
	}

	private function renderBar( string $location, array $regions, array $args ): string {
		$hasCenter      = ! empty( $regions['center'] );
		$wrapperClasses = array( 'imm-toggle-bar' );
		if ( $hasCenter ) {
			$wrapperClasses[] = 'imm-toggle-bar--has-center';
		}

		$wrapperAttrs = array(
			'class'                        => implode( ' ', $wrapperClasses ),
			'data-imm-toggle-bar-location' => esc_attr( $location ),
		);

		$html  = '<div ' . $this->buildAttrs( $wrapperAttrs ) . '>';
		$html .= $this->renderRegion( 'left', $regions['left'], $args );
		if ( $hasCenter ) {
			$html .= $this->renderRegion( 'center', $regions['center'], $args );
		}
		$html .= $this->renderRegion( 'right', $regions['right'], $args );
		$html .= '</div>';

		return $html;
	}

	private function renderRegion( string $align, array $blocks, array $args ): string {
		if ( empty( $blocks ) ) {
			return '';
		}

		$html = '<div class="imm-toggle-bar-' . esc_attr( $align ) . '">';
		foreach ( $blocks as $block ) {
			$html .= $this->renderBlock( $block, $args );
		}
		$html .= '</div>';

		return $html;
	}

	private function renderBlock( array $block, array $args ): string {
		$type          = $block['type'] ?? '';
		$blockInstance = $this->registry->get( $type );

		if ( ! $blockInstance instanceof ToggleBlock ) {
			return '';
		}

		$settings = $block['settings'] ?? array();

		/**
		 * Filter toggle block settings before render.
		 *
		 * @since 1.3.0
		 *
		 * @param array  $settings The validated block settings.
		 * @param string $type     The block type.
		 * @param string $blockId  The block ID.
		 * @param array  $args     Additional render args.
		 */
		$settings = apply_filters( 'imm_toggle_bar_block_settings', $settings, $type, $block['id'] ?? '', $args );

		$blockArgs = array_merge(
			$args,
			array(
				'block_id'   => $block['id'] ?? '',
				'align'      => $block['align'] ?? 'left',
				'block_type' => $type,
			)
		);

		return $blockInstance->render( $settings, $blockArgs );
	}

	private function buildAttrs( array $attrs ): string {
		$out = '';
		foreach ( $attrs as $key => $value ) {
			if ( $value === null || $value === false || $value === '' ) {
				continue;
			}
			$out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
		return $out;
	}

	private function createDefaultRegistry(): ToggleBlockRegistry {
		$registry = new ToggleBlockRegistry();

		/**
		 * Filter the available toggle block types before they are registered.
		 *
		 * @since 1.3.0
		 *
		 * @param ToggleBlockRegistry $registry The registry instance to populate.
		 */
		$registry = apply_filters( 'imm_toggle_bar_blocks', $registry );

		if ( count( $registry->all() ) === 0 ) {
			$registry->register( new Blocks\MenuToggleBlock() );
			$registry->register( new Blocks\AnimatedMenuToggleBlock() );
			$registry->register( new Blocks\SpacerBlock() );
			$registry->register( new Blocks\SearchBlock() );
			$registry->register( new Blocks\LogoBlock() );
			$registry->register( new Blocks\IconBlock() );
			$registry->register( new Blocks\HtmlBlock() );
			$registry->register( new Blocks\CustomBlock() );
		}

		return $registry;
	}

	public function getRegistry(): ToggleBlockRegistry {
		return $this->registry;
	}
}
