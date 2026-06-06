<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class MobileTab implements SettingsTab {

	public function id(): string {
		return 'mobile';
	}

	public function label(): string {
		return __( 'Mobile', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Mobile Breakpoint', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[mobile_breakpoint]"
							value="<?php echo esc_attr( $settings['mobile_breakpoint'] ?? 768 ); ?>"
							min="320"
							max="1200"
							step="16"
							class="small-text"
					/> px
					<p class="description"><?php esc_html_e( 'Viewport width at which the menu switches to mobile mode.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Off-Canvas Direction', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[off_canvas_direction]">
						<option value="right" <?php selected( $settings['off_canvas_direction'] ?? '', 'right' ); ?>><?php esc_html_e( 'Slide from Right', 'imedia-menu' ); ?></option>
						<option value="left" <?php selected( $settings['off_canvas_direction'] ?? '', 'left' ); ?>><?php esc_html_e( 'Slide from Left', 'imedia-menu' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Hamburger Style', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[hamburger_style]">
						<option value="classic" <?php selected( $settings['hamburger_style'] ?? '', 'classic' ); ?>><?php esc_html_e( 'Classic (3 lines)', 'imedia-menu' ); ?></option>
						<option value="x-morph" <?php selected( $settings['hamburger_style'] ?? '', 'x-morph' ); ?>><?php esc_html_e( 'X Morph', 'imedia-menu' ); ?></option>
						<option value="arrow" <?php selected( $settings['hamburger_style'] ?? '', 'arrow' ); ?>><?php esc_html_e( 'Arrow Morph', 'imedia-menu' ); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['mobile_breakpoint'] ) ) {
			$validated['mobile_breakpoint'] = min( 1200, max( 320, (int) $input['mobile_breakpoint'] ) );
		}

		if ( isset( $input['off_canvas_direction'] ) && in_array( $input['off_canvas_direction'], array( 'right', 'left' ), true ) ) {
			$validated['off_canvas_direction'] = $input['off_canvas_direction'];
		}

		if ( isset( $input['hamburger_style'] ) && in_array( $input['hamburger_style'], array( 'classic', 'x-morph', 'arrow' ), true ) ) {
			$validated['hamburger_style'] = $input['hamburger_style'];
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		if ( isset( $input['mobile_breakpoint'] ) ) {
			$sanitized['mobile_breakpoint'] = (int) $input['mobile_breakpoint'];
		}

		if ( isset( $input['off_canvas_direction'] ) ) {
			$sanitized['off_canvas_direction'] = sanitize_text_field( $input['off_canvas_direction'] );
		}

		if ( isset( $input['hamburger_style'] ) ) {
			$sanitized['hamburger_style'] = sanitize_text_field( $input['hamburger_style'] );
		}

		return $sanitized;
	}
}
