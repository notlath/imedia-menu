<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class DesignTab implements SettingsTab {

	public function id(): string {
		return 'design';
	}

	public function label(): string {
		return __( 'Design', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Menu Bar Background', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[menu_bar_bg]"
							value="<?php echo esc_attr( $settings['menu_bar_bg'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#ffffff"
					/>
					<p class="description"><?php esc_html_e( 'Background color or gradient for the menu bar.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Menu Bar Height', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[menu_bar_height]"
							value="<?php echo esc_attr( $settings['menu_bar_height'] ?? 60 ); ?>"
							min="30"
							max="120"
							class="small-text"
					/> px
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Color', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[menu_text_color]"
							value="<?php echo esc_attr( $settings['menu_text_color'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#333333"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Hover Color', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[menu_text_hover]"
							value="<?php echo esc_attr( $settings['menu_text_hover'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#0066cc"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dropdown/Panel Background', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dropdown_bg]"
							value="<?php echo esc_attr( $settings['dropdown_bg'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#ffffff"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Sticky Menu', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[sticky]"
								value="1"
								<?php checked( $settings['sticky'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Make menu sticky (uses CSS position: sticky)', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Transparent Mode', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[transparent_mode]"
								value="1"
								<?php checked( $settings['transparent_mode'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Menu bar overlays content with transparent background', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Dark Mode', 'imedia-menu' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Configure colors for dark mode. These apply when the user\'s system prefers a dark color scheme.', 'imedia-menu' ); ?></p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable Dark Mode', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[dark_mode_enabled]"
								value="1"
								<?php checked( $settings['dark_mode_enabled'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Respect prefers-color-scheme and apply dark mode colors', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dark Menu Bar Background', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dark_mode_bg]"
							value="<?php echo esc_attr( $settings['dark_mode_bg'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#1e1e1e"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dark Text Color', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dark_mode_text]"
							value="<?php echo esc_attr( $settings['dark_mode_text'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#e0e0e0"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dark Text Hover Color', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dark_mode_text_hover]"
							value="<?php echo esc_attr( $settings['dark_mode_text_hover'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#66b3ff"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dark Dropdown Background', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dark_mode_dropdown_bg]"
							value="<?php echo esc_attr( $settings['dark_mode_dropdown_bg'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#2d2d2d"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Dark Dropdown Border', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[dark_mode_dropdown_border]"
							value="<?php echo esc_attr( $settings['dark_mode_dropdown_border'] ?? '' ); ?>"
							class="imedia-color-picker"
							placeholder="#444444"
					/>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['menu_bar_bg'] ) ) {
			$validated['menu_bar_bg'] = $this->validateColor( $input['menu_bar_bg'] );
		}

		if ( isset( $input['menu_bar_height'] ) ) {
			$validated['menu_bar_height'] = min( 120, max( 30, (int) $input['menu_bar_height'] ) );
		}

		if ( isset( $input['menu_text_color'] ) ) {
			$validated['menu_text_color'] = $this->validateColor( $input['menu_text_color'] );
		}

		if ( isset( $input['menu_text_hover'] ) ) {
			$validated['menu_text_hover'] = $this->validateColor( $input['menu_text_hover'] );
		}

		if ( isset( $input['dropdown_bg'] ) ) {
			$validated['dropdown_bg'] = $this->validateColor( $input['dropdown_bg'] );
		}

		if ( isset( $input['sticky'] ) ) {
			$validated['sticky'] = true;
		}

		if ( isset( $input['transparent_mode'] ) ) {
			$validated['transparent_mode'] = true;
		}

		if ( isset( $input['dark_mode_enabled'] ) ) {
			$validated['dark_mode_enabled'] = true;
		}

		if ( isset( $input['dark_mode_bg'] ) ) {
			$validated['dark_mode_bg'] = $this->validateColor( $input['dark_mode_bg'] );
		}

		if ( isset( $input['dark_mode_text'] ) ) {
			$validated['dark_mode_text'] = $this->validateColor( $input['dark_mode_text'] );
		}

		if ( isset( $input['dark_mode_text_hover'] ) ) {
			$validated['dark_mode_text_hover'] = $this->validateColor( $input['dark_mode_text_hover'] );
		}

		if ( isset( $input['dark_mode_dropdown_bg'] ) ) {
			$validated['dark_mode_dropdown_bg'] = $this->validateColor( $input['dark_mode_dropdown_bg'] );
		}

		if ( isset( $input['dark_mode_dropdown_border'] ) ) {
			$validated['dark_mode_dropdown_border'] = $this->validateColor( $input['dark_mode_dropdown_border'] );
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		if ( isset( $input['menu_bar_bg'] ) ) {
			$sanitized['menu_bar_bg'] = sanitize_text_field( $input['menu_bar_bg'] );
		}

		if ( isset( $input['menu_bar_height'] ) ) {
			$sanitized['menu_bar_height'] = (int) $input['menu_bar_height'];
		}

		if ( isset( $input['menu_text_color'] ) ) {
			$sanitized['menu_text_color'] = sanitize_text_field( $input['menu_text_color'] );
		}

		if ( isset( $input['menu_text_hover'] ) ) {
			$sanitized['menu_text_hover'] = sanitize_text_field( $input['menu_text_hover'] );
		}

		if ( isset( $input['dropdown_bg'] ) ) {
			$sanitized['dropdown_bg'] = sanitize_text_field( $input['dropdown_bg'] );
		}

		if ( isset( $input['sticky'] ) ) {
			$sanitized['sticky'] = (bool) $input['sticky'];
		}

		if ( isset( $input['transparent_mode'] ) ) {
			$sanitized['transparent_mode'] = (bool) $input['transparent_mode'];
		}

		if ( isset( $input['dark_mode_enabled'] ) ) {
			$sanitized['dark_mode_enabled'] = (bool) $input['dark_mode_enabled'];
		}

		if ( isset( $input['dark_mode_bg'] ) ) {
			$sanitized['dark_mode_bg'] = sanitize_text_field( $input['dark_mode_bg'] );
		}

		if ( isset( $input['dark_mode_text'] ) ) {
			$sanitized['dark_mode_text'] = sanitize_text_field( $input['dark_mode_text'] );
		}

		if ( isset( $input['dark_mode_text_hover'] ) ) {
			$sanitized['dark_mode_text_hover'] = sanitize_text_field( $input['dark_mode_text_hover'] );
		}

		if ( isset( $input['dark_mode_dropdown_bg'] ) ) {
			$sanitized['dark_mode_dropdown_bg'] = sanitize_text_field( $input['dark_mode_dropdown_bg'] );
		}

		if ( isset( $input['dark_mode_dropdown_border'] ) ) {
			$sanitized['dark_mode_dropdown_border'] = sanitize_text_field( $input['dark_mode_dropdown_border'] );
		}

		return $sanitized;
	}

	private function validateColor( string $value ): string {
		$value = trim( $value );

		if ( $value === '' ) {
			return '';
		}

		if ( preg_match( '/^(#[0-9a-fA-F]{3,8}|rgba?\([^)]+\)|hsla?\([^)]+\))$/', $value ) ) {
			return $value;
		}

		return '';
	}
}
