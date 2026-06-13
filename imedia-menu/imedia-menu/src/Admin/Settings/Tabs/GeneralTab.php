<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class GeneralTab implements SettingsTab {

	public function id(): string {
		return 'general';
	}

	public function label(): string {
		return __( 'General', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable iMedia Menu', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[enabled]"
								value="1"
								<?php checked( $settings['enabled'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Replace WordPress menus with iMedia Menu on the frontend', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Default Trigger Type', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[trigger_type]">
						<option value="hover" <?php selected( $settings['trigger_type'] ?? '', 'hover' ); ?>><?php esc_html_e( 'Hover', 'imedia-menu' ); ?></option>
						<option value="click" <?php selected( $settings['trigger_type'] ?? '', 'click' ); ?>><?php esc_html_e( 'Click', 'imedia-menu' ); ?></option>
						<option value="hover_click" <?php selected( $settings['trigger_type'] ?? '', 'hover_click' ); ?>><?php esc_html_e( 'Hover + Click', 'imedia-menu' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Hover Intent Delay', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[hover_delay]"
							value="<?php echo esc_attr( $settings['hover_delay'] ?? 200 ); ?>"
							min="0"
							max="500"
							step="50"
							class="small-text"
					/>
					<p class="description"><?php esc_html_e( 'Milliseconds before a submenu opens on hover (0-500ms).', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Default Animation', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[default_animation]">
						<option value="fade" <?php selected( $settings['default_animation'] ?? '', 'fade' ); ?>><?php esc_html_e( 'Fade', 'imedia-menu' ); ?></option>
						<option value="slide" <?php selected( $settings['default_animation'] ?? '', 'slide' ); ?>><?php esc_html_e( 'Slide Down', 'imedia-menu' ); ?></option>
						<option value="none" <?php selected( $settings['default_animation'] ?? '', 'none' ); ?>><?php esc_html_e( 'None', 'imedia-menu' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Animation Duration', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[animation_duration]"
							value="<?php echo esc_attr( $settings['animation_duration'] ?? 200 ); ?>"
							min="0"
							max="1000"
							step="50"
							class="small-text"
					/> ms
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Admin Bar Preview Link', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[admin_bar_preview]"
								value="1"
								<?php checked( $settings['admin_bar_preview'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Show iMedia Menu link in the admin bar', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['enabled'] ) ) {
			$validated['enabled'] = true;
		}

		if ( isset( $input['trigger_type'] ) && in_array( $input['trigger_type'], array( 'hover', 'click', 'hover_click' ), true ) ) {
			$validated['trigger_type'] = $input['trigger_type'];
		}

		if ( isset( $input['hover_delay'] ) ) {
			$validated['hover_delay'] = min( 500, max( 0, (int) $input['hover_delay'] ) );
		}

		if ( isset( $input['default_animation'] ) && in_array( $input['default_animation'], array( 'fade', 'slide', 'none' ), true ) ) {
			$validated['default_animation'] = $input['default_animation'];
		}

		if ( isset( $input['animation_duration'] ) ) {
			$validated['animation_duration'] = min( 1000, max( 0, (int) $input['animation_duration'] ) );
		}

		if ( isset( $input['admin_bar_preview'] ) ) {
			$validated['admin_bar_preview'] = true;
		}

		return $validated;
	}

	public function sanitize( ?array $input ): array {
		$sanitized = array();

		if ( isset( $input['enabled'] ) ) {
			$sanitized['enabled'] = (bool) $input['enabled'];
		}

		if ( isset( $input['trigger_type'] ) ) {
			$sanitized['trigger_type'] = sanitize_text_field( $input['trigger_type'] );
		}

		if ( isset( $input['hover_delay'] ) ) {
			$sanitized['hover_delay'] = (int) $input['hover_delay'];
		}

		if ( isset( $input['default_animation'] ) ) {
			$sanitized['default_animation'] = sanitize_text_field( $input['default_animation'] );
		}

		if ( isset( $input['animation_duration'] ) ) {
			$sanitized['animation_duration'] = (int) $input['animation_duration'];
		}

		if ( isset( $input['admin_bar_preview'] ) ) {
			$sanitized['admin_bar_preview'] = (bool) $input['admin_bar_preview'];
		}

		return $sanitized;
	}
}
