<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class PhpCallbackCondition implements VisibilityCondition {

	public function type(): string {
		return 'php_callback';
	}

	public function label(): string {
		return __( 'Custom PHP Callback', 'imedia-menu' );
	}

	public function evaluate( array $config ): bool {
		$callback = $config['callback'] ?? '';

		if ( empty( $callback ) ) {
			return true;
		}

		if ( ! is_callable( $callback ) ) {
			_doing_it_wrong(
				__METHOD__,
				sprintf(
					__( 'iMedia Menu visibility callback "%s" is not callable.', 'imedia-menu' ),
					$callback
				),
				'1.0.0'
			);
			return true;
		}

		$result = call_user_func( $callback );

		return (bool) $result;
	}
}
