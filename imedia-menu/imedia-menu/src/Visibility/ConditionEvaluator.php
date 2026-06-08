<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility;

use IMedia\Menu\Contracts\VisibilityCondition;
use IMedia\Menu\Visibility\Conditions\LoginStateCondition;
use IMedia\Menu\Visibility\Conditions\UserRoleCondition;
use IMedia\Menu\Visibility\Conditions\DeviceTypeCondition;
use IMedia\Menu\Visibility\Conditions\PageCondition;
use IMedia\Menu\Visibility\Conditions\ScheduleCondition;
use IMedia\Menu\Visibility\Conditions\LanguageCondition;
use IMedia\Menu\Visibility\Conditions\UrlParameterCondition;
use IMedia\Menu\Visibility\Conditions\PhpCallbackCondition;

final class ConditionEvaluator {

	/** @var array<string, VisibilityCondition> */
	private array $conditions = array();

	public function __construct() {
		$this->registerDefaults();
	}

	public function register( VisibilityCondition $condition ): void {
		$this->conditions[ $condition->type() ] = $condition;
	}

	public function get( string $type ): ?VisibilityCondition {
		return $this->conditions[ $type ] ?? null;
	}

	public function getAll(): array {
		return $this->conditions;
	}

	public function isItemVisible( object $item ): bool {
		$visibility = get_post_meta( $item->ID, '_imedia_menu_visibility', true );

		if ( empty( $visibility ) ) {
			return true;
		}

		$conditions = is_string( $visibility ) ? json_decode( $visibility, true ) : $visibility;

		if ( empty( $conditions ) ) {
			return true;
		}

		return $this->evaluateConditions( $conditions );
	}

	public function isBlockVisible( array $block ): bool {
		$conditions = $block['visibility'] ?? array();

		if ( empty( $conditions ) ) {
			return true;
		}

		return $this->evaluateConditions( $conditions );
	}

	private function evaluateConditions( array $conditions ): bool {
		$settings        = get_option( 'imedia_menu_settings', array() );
		$defaultBehavior = $settings['default_visibility_behavior'] ?? 'all';

		foreach ( $conditions as $condition ) {
			$type   = $condition['type'] ?? '';
			$config = $condition['config'] ?? array();

			$handler = $this->get( $type );

			if ( $handler === null ) {
				continue;
			}

			$result = $handler->evaluate( $config );

			if ( $defaultBehavior === 'all' && ! $result ) {
				return false;
			}

			if ( $defaultBehavior === 'any' && $result ) {
				return true;
			}
		}

		return $defaultBehavior === 'all';
	}

	private function registerDefaults(): void {
		$defaults = array(
			new LoginStateCondition(),
			new UserRoleCondition(),
			new DeviceTypeCondition(),
			new PageCondition(),
			new ScheduleCondition(),
			new LanguageCondition(),
			new UrlParameterCondition(),
			new PhpCallbackCondition(),
		);

		foreach ( $defaults as $condition ) {
			$this->register( $condition );
		}
	}

	public static function getDefaultBehavior(): string {
		$settings = get_option( 'imedia_menu_settings', array() );
		return $settings['default_visibility_behavior'] ?? 'all';
	}
}
