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
								class="imedia-sticky-toggle"
						/>
						<?php esc_html_e( 'Make menu sticky (uses CSS position: sticky)', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr class="imedia-sticky-options">
				<th scope="row"><?php esc_html_e( 'Sticky On', 'imedia-menu' ); ?></th>
				<td>
					<label style="margin-right:16px;">
						<input type="checkbox"
								name="imedia_menu_settings[sticky_desktop]"
								value="1"
								<?php checked( $settings['sticky_desktop'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Desktop', 'imedia-menu' ); ?>
					</label>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[sticky_mobile]"
								value="1"
								<?php checked( $settings['sticky_mobile'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Mobile', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr class="imedia-sticky-options">
				<th scope="row"><?php esc_html_e( 'Sticky Opacity', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[sticky_opacity]"
							value="<?php echo esc_attr( $settings['sticky_opacity'] ?? 1.0 ); ?>"
							min="0.2"
							max="1.0"
							step="0.1"
							class="small-text"
					/>
					<p class="description"><?php esc_html_e( 'Transparency of the menu when stuck (0.2 - 1.0). Default: 1.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr class="imedia-sticky-options">
				<th scope="row"><?php esc_html_e( 'Sticky Offset', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[sticky_offset]"
							value="<?php echo esc_attr( $settings['sticky_offset'] ?? 0 ); ?>"
							min="0"
							max="500"
							step="1"
							class="small-text"
					/> px
					<p class="description"><?php esc_html_e( 'Distance between the top of the window and the menu when stuck. Default: 0.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr class="imedia-sticky-options">
				<th scope="row"><?php esc_html_e( 'Expand Background', 'imedia-menu' ); ?></th>
				<td>
					<label style="margin-right:16px;">
						<input type="checkbox"
								name="imedia_menu_settings[sticky_expand]"
								value="1"
								<?php checked( $settings['sticky_expand'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Desktop', 'imedia-menu' ); ?>
					</label>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[sticky_expand_mobile]"
								value="1"
								<?php checked( $settings['sticky_expand_mobile'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Mobile', 'imedia-menu' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Expand the background to fill the page width when the menu becomes sticky.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr class="imedia-sticky-options">
				<th scope="row"><?php esc_html_e( 'Hide Until Scroll Up', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[sticky_hide_until_scroll_up]"
								value="1"
								<?php checked( $settings['sticky_hide_until_scroll_up'] ?? false ); ?>
								class="imedia-sticky-hide-toggle"
						/>
						<?php esc_html_e( 'Hide menu as user scrolls down; reveal on scroll up.', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr class="imedia-sticky-options imedia-sticky-hide-options">
				<th scope="row"><?php esc_html_e( 'Hide Tolerance', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[sticky_hide_until_scroll_up_tolerance]"
							value="<?php echo esc_attr( $settings['sticky_hide_until_scroll_up_tolerance'] ?? 10 ); ?>"
							min="0"
							max="200"
							step="1"
							class="small-text"
					/> px
					<p class="description"><?php esc_html_e( 'Minimum scroll distance to trigger hide. Default: 10.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr class="imedia-sticky-options imedia-sticky-hide-options">
				<th scope="row"><?php esc_html_e( 'Hide Offset', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[sticky_hide_until_scroll_up_offset]"
							value="<?php echo esc_attr( $settings['sticky_hide_until_scroll_up_offset'] ?? 0 ); ?>"
							min="0"
							max="500"
							step="1"
							class="small-text"
					/> px
					<p class="description"><?php esc_html_e( 'Scroll distance before the menu is allowed to hide. Default: 0.', 'imedia-menu' ); ?></p>
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

		<h2><?php esc_html_e( 'Badges', 'imedia-menu' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Global default colors for the 4 badge styles. These apply to badges that do not override them per item.', 'imedia-menu' ); ?></p>
		<?php
		$badgeStyles = array(
			'1' => array(
				'label'        => __( 'Badge Style 1 (Red)', 'imedia-menu' ),
				'bg_default'   => '#D32F2F',
				'text_default' => '#ffffff',
			),
			'2' => array(
				'label'        => __( 'Badge Style 2 (Teal)', 'imedia-menu' ),
				'bg_default'   => '#00796B',
				'text_default' => '#ffffff',
			),
			'3' => array(
				'label'        => __( 'Badge Style 3 (Amber)', 'imedia-menu' ),
				'bg_default'   => '#FFC107',
				'text_default' => '#ffffff',
			),
			'4' => array(
				'label'        => __( 'Badge Style 4 (Indigo)', 'imedia-menu' ),
				'bg_default'   => '#303F9F',
				'text_default' => '#ffffff',
			),
		);
		foreach ( $badgeStyles as $num => $style ) :
			$bgKey   = "badge_{$num}_bg";
			$textKey = "badge_{$num}_text";
			?>
		<h3 style="margin:16px 0 4px;"><?php echo esc_html( $style['label'] ); ?></h3>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Background', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[<?php echo esc_attr( $bgKey ); ?>]"
							value="<?php echo esc_attr( $settings[ $bgKey ] ?? $style['bg_default'] ); ?>"
							class="imedia-color-picker"
					/>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Color', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[<?php echo esc_attr( $textKey ); ?>]"
							value="<?php echo esc_attr( $settings[ $textKey ] ?? $style['text_default'] ); ?>"
							class="imedia-color-picker"
					/>
				</td>
			</tr>
		</table>
		<?php endforeach; ?>

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

		if ( isset( $input['sticky_desktop'] ) ) {
			$validated['sticky_desktop'] = true;
		}

		if ( isset( $input['sticky_mobile'] ) ) {
			$validated['sticky_mobile'] = true;
		}

		if ( isset( $input['sticky_opacity'] ) ) {
			$opacity                     = (float) $input['sticky_opacity'];
			$validated['sticky_opacity'] = min( 1.0, max( 0.2, $opacity ) );
		}

		if ( isset( $input['sticky_offset'] ) ) {
			$offset                     = (int) $input['sticky_offset'];
			$validated['sticky_offset'] = min( 500, max( 0, $offset ) );
		}

		if ( isset( $input['sticky_expand'] ) ) {
			$validated['sticky_expand'] = true;
		}

		if ( isset( $input['sticky_expand_mobile'] ) ) {
			$validated['sticky_expand_mobile'] = true;
		}

		if ( isset( $input['sticky_hide_until_scroll_up'] ) ) {
			$validated['sticky_hide_until_scroll_up'] = true;
		}

		if ( isset( $input['sticky_hide_until_scroll_up_tolerance'] ) ) {
			$tolerance = (int) $input['sticky_hide_until_scroll_up_tolerance'];
			$validated['sticky_hide_until_scroll_up_tolerance'] = min( 200, max( 0, $tolerance ) );
		}

		if ( isset( $input['sticky_hide_until_scroll_up_offset'] ) ) {
			$offset = (int) $input['sticky_hide_until_scroll_up_offset'];
			$validated['sticky_hide_until_scroll_up_offset'] = min( 500, max( 0, $offset ) );
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

		foreach ( array( 1, 2, 3, 4 ) as $num ) {
			$bgKey   = "badge_{$num}_bg";
			$textKey = "badge_{$num}_text";
			if ( isset( $input[ $bgKey ] ) ) {
				$validated[ $bgKey ] = $this->validateColor( $input[ $bgKey ] );
			}
			if ( isset( $input[ $textKey ] ) ) {
				$validated[ $textKey ] = $this->validateColor( $input[ $textKey ] );
			}
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

		if ( isset( $input['sticky_desktop'] ) ) {
			$sanitized['sticky_desktop'] = (bool) $input['sticky_desktop'];
		}

		if ( isset( $input['sticky_mobile'] ) ) {
			$sanitized['sticky_mobile'] = (bool) $input['sticky_mobile'];
		}

		if ( isset( $input['sticky_opacity'] ) ) {
			$sanitized['sticky_opacity'] = (float) $input['sticky_opacity'];
		}

		if ( isset( $input['sticky_offset'] ) ) {
			$sanitized['sticky_offset'] = (int) $input['sticky_offset'];
		}

		if ( isset( $input['sticky_expand'] ) ) {
			$sanitized['sticky_expand'] = (bool) $input['sticky_expand'];
		}

		if ( isset( $input['sticky_expand_mobile'] ) ) {
			$sanitized['sticky_expand_mobile'] = (bool) $input['sticky_expand_mobile'];
		}

		if ( isset( $input['sticky_hide_until_scroll_up'] ) ) {
			$sanitized['sticky_hide_until_scroll_up'] = (bool) $input['sticky_hide_until_scroll_up'];
		}

		if ( isset( $input['sticky_hide_until_scroll_up_tolerance'] ) ) {
			$sanitized['sticky_hide_until_scroll_up_tolerance'] = (int) $input['sticky_hide_until_scroll_up_tolerance'];
		}

		if ( isset( $input['sticky_hide_until_scroll_up_offset'] ) ) {
			$sanitized['sticky_hide_until_scroll_up_offset'] = (int) $input['sticky_hide_until_scroll_up_offset'];
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

		foreach ( array( 1, 2, 3, 4 ) as $num ) {
			$bgKey   = "badge_{$num}_bg";
			$textKey = "badge_{$num}_text";
			if ( isset( $input[ $bgKey ] ) ) {
				$sanitized[ $bgKey ] = sanitize_text_field( $input[ $bgKey ] );
			}
			if ( isset( $input[ $textKey ] ) ) {
				$sanitized[ $textKey ] = sanitize_text_field( $input[ $textKey ] );
			}
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
