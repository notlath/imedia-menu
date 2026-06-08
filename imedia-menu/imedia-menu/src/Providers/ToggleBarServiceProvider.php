<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Frontend\ToggleBar\ToggleBarRenderer;
use IMedia\Menu\Frontend\ToggleBar\ToggleBarRepository;

final class ToggleBarServiceProvider {

	/**
	 * Toggle bar renderer used to output HTML.
	 *
	 * @var ToggleBarRenderer
	 */
	private ToggleBarRenderer $renderer;

	/**
	 * Toggle bar storage repository.
	 *
	 * @var ToggleBarRepository
	 */
	private ToggleBarRepository $repository;

	public function __construct( ?ToggleBarRenderer $renderer = null, ?ToggleBarRepository $repository = null ) {
		$this->repository = $repository ?? new ToggleBarRepository();
		$this->renderer   = $renderer ?? new ToggleBarRenderer( $this->repository );
	}

	public function register(): void {
	}

	public function boot(): void {
		add_action( 'wp_footer', array( $this, 'renderToggleBar' ), 25 );
		add_action( 'imm_after_mobile_toggle', array( $this, 'renderToggleBarForCurrentLocation' ) );
	}

	public function renderToggleBar(): void {
		if ( ! $this->repository->anyLocationHasBlocks() ) {
			return;
		}

		$html = $this->renderer->renderForAllLocations();
		if ( $html ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered HTML from ToggleBlock::render.
		}
	}

	public function renderToggleBarForCurrentLocation(): void {
		$location = $this->getCurrentLocation();
		if ( ! $location ) {
			return;
		}

		$html = $this->renderer->render( $location );
		if ( $html ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered HTML from ToggleBlock::render.
		}
	}

	public function getRenderer(): ToggleBarRenderer {
		return $this->renderer;
	}

	public function getRepository(): ToggleBarRepository {
		return $this->repository;
	}

	private function getCurrentLocation(): ?string {
		$locations = get_nav_menu_locations();
		if ( ! is_array( $locations ) ) {
			return null;
		}
		foreach ( $locations as $slug => $menuId ) {
			if ( has_nav_menu( $slug ) ) {
				return $slug;
			}
		}
		return null;
	}
}
