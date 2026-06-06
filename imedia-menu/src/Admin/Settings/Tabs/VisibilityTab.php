<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class VisibilityTab implements SettingsTab {

	public function id(): string {
		return 'visibility';
	}

	public function label(): string {
		return __( 'Visibility', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Default Visibility Behavior', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[visibility_default_behavior]">
						<option value="show_all" <?php selected( $settings['visibility_default_behavior'] ?? '', 'show_all' ); ?>><?php esc_html_e( 'Show all items by default', 'imedia-menu' ); ?></option>
						<option value="require_conditions" <?php selected( $settings['visibility_default_behavior'] ?? '', 'require_conditions' ); ?>><?php esc_html_e( 'Hide items without visibility conditions', 'imedia-menu' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Controls how menu items behave when no visibility conditions are configured.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Locale Detection Method', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[locale_detection_method]">
						<option value="auto" <?php selected( $settings['locale_detection_method'] ?? '', 'auto' ); ?>><?php esc_html_e( 'Auto-detect', 'imedia-menu' ); ?></option>
						<option value="wpml" <?php selected( $settings['locale_detection_method'] ?? '', 'wpml' ); ?>><?php esc_html_e( 'WPML', 'imedia-menu' ); ?></option>
						<option value="polylang" <?php selected( $settings['locale_detection_method'] ?? '', 'polylang' ); ?>><?php esc_html_e( 'Polylang', 'imedia-menu' ); ?></option>
						<option value="translatepress" <?php selected( $settings['locale_detection_method'] ?? '', 'translatepress' ); ?>><?php esc_html_e( 'TranslatePress', 'imedia-menu' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Which multilingual plugin to use for locale-based visibility conditions.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Custom PHP Callbacks', 'imedia-menu' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Use the imedia_menu_item_visible filter to add custom visibility logic in your theme\'s functions.php:', 'imedia-menu' ); ?>
		</p>
		<pre style="background:#f0f0f1;padding:12px;border-radius:4px;overflow-x:auto;max-width:600px;">
add_filter( 'imedia_menu_item_visible', function ( $visible, $item, $conditions ) {
	if ( in_array( 'special-page', $item-&gt;classes, true ) ) {
		return is_page( 'secret' );
	}
	return $visible;
}, 10, 3 );</pre>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['visibility_default_behavior'] ) && in_array( $input['visibility_default_behavior'], array( 'show_all', 'require_conditions' ), true ) ) {
			$validated['visibility_default_behavior'] = $input['visibility_default_behavior'];
		}

		if ( isset( $input['locale_detection_method'] ) && in_array( $input['locale_detection_method'], array( 'auto', 'wpml', 'polylang', 'translatepress' ), true ) ) {
			$validated['locale_detection_method'] = $input['locale_detection_method'];
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		if ( isset( $input['visibility_default_behavior'] ) ) {
			$sanitized['visibility_default_behavior'] = sanitize_text_field( $input['visibility_default_behavior'] );
		}

		if ( isset( $input['locale_detection_method'] ) ) {
			$sanitized['locale_detection_method'] = sanitize_text_field( $input['locale_detection_method'] );
		}

		return $sanitized;
	}
}
