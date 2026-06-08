<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Divi\location\server;

use WP_REST_Request;
use WP_REST_Response;

final class Index {

	public static function render( WP_REST_Request $request ): WP_REST_Response {
		$location = sanitize_title( $request->get_param( 'location' ) );

		$html = wp_nav_menu(
			array(
				'theme_location' => $location,
				'echo'           => false,
				'fallback_cb'    => '__return_false',
			)
		);

		return new WP_REST_Response( array( 'html' => $html ), 200 );
	}
}
