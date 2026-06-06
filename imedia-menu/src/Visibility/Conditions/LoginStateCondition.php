<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class LoginStateCondition implements VisibilityCondition {

	public function type(): string {
		return 'login_state';
	}

	public function label(): string {
		return __( 'User Login State', 'imedia-menu' );
	}

	public function evaluate( array $config ): bool {
		$state = $config['state'] ?? 'logged_in';

		return match ( $state ) {
			'logged_in'  => is_user_logged_in(),
			'logged_out' => ! is_user_logged_in(),
			default      => true,
		};
	}
}
