<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class AnimationsTab implements SettingsTab {

	public function id(): string {
		return 'animations';
	}

	public function label(): string {
		return __( 'Animations', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Animation Easing', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[animation_easing]">
						<option value="ease" <?php selected( $settings['animation_easing'] ?? '', 'ease' ); ?>><?php esc_html_e( 'Ease', 'imedia-menu' ); ?></option>
						<option value="ease-in" <?php selected( $settings['animation_easing'] ?? '', 'ease-in' ); ?>><?php esc_html_e( 'Ease In', 'imedia-menu' ); ?></option>
						<option value="ease-out" <?php selected( $settings['animation_easing'] ?? '', 'ease-out' ); ?>><?php esc_html_e( 'Ease Out', 'imedia-menu' ); ?></option>
						<option value="ease-in-out" <?php selected( $settings['animation_easing'] ?? '', 'ease-in-out' ); ?>><?php esc_html_e( 'Ease In-Out', 'imedia-menu' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'CSS easing function for panel open/close animations.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Reduced Motion', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[reduced_motion]"
								value="1"
								<?php checked( $settings['reduced_motion'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Respect prefers-reduced-motion and disable animations', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['animation_easing'] ) && in_array( $input['animation_easing'], array( 'ease', 'ease-in', 'ease-out', 'ease-in-out' ), true ) ) {
			$validated['animation_easing'] = $input['animation_easing'];
		}

		if ( isset( $input['reduced_motion'] ) ) {
			$validated['reduced_motion'] = true;
		}

		return $validated;
	}

	public function sanitize( ?array $input ): array {
		$sanitized = array();

		if ( isset( $input['animation_easing'] ) ) {
			$sanitized['animation_easing'] = sanitize_text_field( $input['animation_easing'] );
		}

		if ( isset( $input['reduced_motion'] ) ) {
			$sanitized['reduced_motion'] = (bool) $input['reduced_motion'];
		}

		return $sanitized;
	}
}
