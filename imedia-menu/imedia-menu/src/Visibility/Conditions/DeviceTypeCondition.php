<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility\Conditions;

use IMedia\Menu\Contracts\VisibilityCondition;

final class DeviceTypeCondition implements VisibilityCondition {

	public function type(): string {
		return 'device_type';
	}

	public function label(): string {
		return __( 'Device Type', 'imedia-menu' );
	}

	public function evaluate( array $config ): bool {
		$allowedDevices = $config['devices'] ?? array();

		if ( empty( $allowedDevices ) ) {
			return true;
		}

		$isMobile = function_exists( 'wp_is_mobile' ) && wp_is_mobile();

		$currentDevice = $isMobile ? 'mobile' : 'desktop';

		return in_array( $currentDevice, $allowedDevices, true );
	}
}
