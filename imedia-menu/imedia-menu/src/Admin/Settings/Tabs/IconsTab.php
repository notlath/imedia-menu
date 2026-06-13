<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class IconsTab implements SettingsTab {

	public function id(): string {
		return 'icons';
	}

	public function label(): string {
		return __( 'Icons', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		$providers = $settings['icon_providers'] ?? array();
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Icon Providers', 'imedia-menu' ); ?></th>
				<td>
					<fieldset>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][dashicons]"
									value="1"
									<?php checked( $providers['dashicons'] ?? true ); ?>
							/>
							<?php esc_html_e( 'Dashicons (WordPress core)', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][fontawesome]"
									value="1"
									<?php checked( $providers['fontawesome'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Font Awesome 4', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][fontawesome5]"
									value="1"
									<?php checked( $providers['fontawesome5'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Font Awesome 5', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][fontawesome6]"
									value="1"
									<?php checked( $providers['fontawesome6'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Font Awesome 6', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][genericons]"
									value="1"
									<?php checked( $providers['genericons'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Genericons', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][bootstrap_icons]"
									value="1"
									<?php checked( $providers['bootstrap_icons'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Bootstrap Icons', 'imedia-menu' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox"
									name="imedia_menu_settings[icon_providers][custom_svg]"
									value="1"
									<?php checked( $providers['custom_svg'] ?? false ); ?>
							/>
							<?php esc_html_e( 'Custom SVG Uploads', 'imedia-menu' ); ?>
						</label>
					</fieldset>
					<p class="description"><?php esc_html_e( 'Select which icon sources are available when choosing icons for menu items.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Font Awesome Source', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[fontawesome_source]">
						<option value="cdn" <?php selected( $settings['fontawesome_source'] ?? '', 'cdn' ); ?>><?php esc_html_e( 'CDN (jsDelivr)', 'imedia-menu' ); ?></option>
						<option value="local" <?php selected( $settings['fontawesome_source'] ?? '', 'local' ); ?>><?php esc_html_e( 'Local (theme or plugin)', 'imedia-menu' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Font Awesome Version', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[fontawesome_version]"
							value="<?php echo esc_attr( $settings['fontawesome_version'] ?? '6.5.1' ); ?>"
							placeholder="6.5.1"
							class="regular-text"
					/>
					<p class="description"><?php esc_html_e( 'Version number used for the CDN URL (only used when CDN source is selected).', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Font Awesome CDN URL', 'imedia-menu' ); ?></th>
				<td>
					<input type="text"
							name="imedia_menu_settings[fontawesome_cdn_url]"
							value="<?php echo esc_attr( $settings['fontawesome_cdn_url'] ?? '' ); ?>"
							placeholder="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css"
							class="large-text"
					/>
					<p class="description"><?php esc_html_e( 'Override the default CDN URL. Leave empty to use the jsDelivr default based on the version above.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['icon_providers'] ) && is_array( $input['icon_providers'] ) ) {
			$validated['icon_providers'] = array(
				'dashicons'       => isset( $input['icon_providers']['dashicons'] ),
				'fontawesome'     => isset( $input['icon_providers']['fontawesome'] ),
				'fontawesome5'    => isset( $input['icon_providers']['fontawesome5'] ),
				'fontawesome6'    => isset( $input['icon_providers']['fontawesome6'] ),
				'genericons'      => isset( $input['icon_providers']['genericons'] ),
				'bootstrap_icons' => isset( $input['icon_providers']['bootstrap_icons'] ),
				'custom_svg'      => isset( $input['icon_providers']['custom_svg'] ),
			);
		}

		if ( isset( $input['fontawesome_source'] ) && in_array( $input['fontawesome_source'], array( 'cdn', 'local' ), true ) ) {
			$validated['fontawesome_source'] = $input['fontawesome_source'];
		}

		if ( isset( $input['fontawesome_version'] ) ) {
			$validated['fontawesome_version'] = sanitize_text_field( $input['fontawesome_version'] );
		}

		if ( isset( $input['fontawesome_cdn_url'] ) ) {
			$url = sanitize_url( $input['fontawesome_cdn_url'] );

			if ( $url === '' || filter_var( $url, FILTER_VALIDATE_URL ) ) {
				$validated['fontawesome_cdn_url'] = $url;
			}
		}

		return $validated;
	}

	public function sanitize( ?array $input ): array {
		$sanitized = array();

		if ( isset( $input['icon_providers'] ) && is_array( $input['icon_providers'] ) ) {
			$sanitized['icon_providers'] = array(
				'dashicons'       => (bool) ( $input['icon_providers']['dashicons'] ?? false ),
				'fontawesome'     => (bool) ( $input['icon_providers']['fontawesome'] ?? false ),
				'fontawesome5'    => (bool) ( $input['icon_providers']['fontawesome5'] ?? false ),
				'fontawesome6'    => (bool) ( $input['icon_providers']['fontawesome6'] ?? false ),
				'genericons'      => (bool) ( $input['icon_providers']['genericons'] ?? false ),
				'bootstrap_icons' => (bool) ( $input['icon_providers']['bootstrap_icons'] ?? false ),
				'custom_svg'      => (bool) ( $input['icon_providers']['custom_svg'] ?? false ),
			);
		}

		if ( isset( $input['fontawesome_source'] ) ) {
			$sanitized['fontawesome_source'] = sanitize_text_field( $input['fontawesome_source'] );
		}

		if ( isset( $input['fontawesome_version'] ) ) {
			$sanitized['fontawesome_version'] = sanitize_text_field( $input['fontawesome_version'] );
		}

		if ( isset( $input['fontawesome_cdn_url'] ) ) {
			$sanitized['fontawesome_cdn_url'] = esc_url_raw( $input['fontawesome_cdn_url'] );
		}

		return $sanitized;
	}
}
