<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend\PanelLayout;

use IMedia\Menu\ContentBlocks\Registry;
use IMedia\Menu\Enums\PanelLayoutType;
use IMedia\Menu\Visibility\ConditionEvaluator;

/**
 * Resolves a PanelLayoutType to its strategy implementation.
 *
 * Strategies are instantiated lazily on first request; the registry
 * constructor is the only place that wires shared dependencies
 * (ContentBlocks Registry, ConditionEvaluator).
 */
final class PanelLayoutStrategyRegistry {

	private Registry $registry;
	private ConditionEvaluator $evaluator;

	/** @var array<string, PanelLayoutStrategy> */
	private array $instances = array();

	public function __construct( Registry $registry, ConditionEvaluator $evaluator ) {
		$this->registry  = $registry;
		$this->evaluator = $evaluator;
	}

	public function get( PanelLayoutType $layout ): PanelLayoutStrategy {
		$key = $layout->value;

		if ( ! isset( $this->instances[ $key ] ) ) {
			$this->instances[ $key ] = $this->create( $layout );
		}

		return $this->instances[ $key ];
	}

	/**
	 * Returns the unique stylesheets required by the given set of layouts.
	 * Used by Assets to enqueue conditionally.
	 *
	 * @param PanelLayoutType[] $layouts
	 * @return string[]
	 */
	public function requiredStylesheets( array $layouts ): array {
		$files = array();

		foreach ( $layouts as $layout ) {
			$file = $this->get( $layout )->requiredStylesheet();
			if ( $file !== null && ! in_array( $file, $files, true ) ) {
				$files[] = $file;
			}
		}

		return $files;
	}

	private function create( PanelLayoutType $layout ): PanelLayoutStrategy {
		return match ( $layout ) {
			PanelLayoutType::Standard => new StandardLayout( $this->registry, $this->evaluator ),
			PanelLayoutType::Grid     => new GridLayout( $this->registry, $this->evaluator ),
			PanelLayoutType::Flyout   => new FlyoutLayout(),
		};
	}
}
