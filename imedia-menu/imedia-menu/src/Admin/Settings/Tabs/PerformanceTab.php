<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class PerformanceTab implements SettingsTab {

	public function id(): string {
		return 'performance';
	}

	public function label(): string {
		return __( 'Performance', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable Caching', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[enable_caching]"
								value="1"
								<?php checked( $settings['enable_caching'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Cache rendered menus for better performance', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Cache Duration', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[cache_duration]"
							value="<?php echo esc_attr( $settings['cache_duration'] ?? 60 ); ?>"
							min="1"
							max="1440"
							class="small-text"
					/> <?php esc_html_e( 'minutes', 'imedia-menu' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Code Splitting', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[code_splitting]"
								value="1"
								<?php checked( $settings['code_splitting'] ?? true ); ?>
						/>
						<?php esc_html_e( 'Load only the assets needed for each page', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['enable_caching'] ) ) {
			$validated['enable_caching'] = true;
		}

		if ( isset( $input['cache_duration'] ) ) {
			$validated['cache_duration'] = min( 1440, max( 1, (int) $input['cache_duration'] ) );
		}

		if ( isset( $input['code_splitting'] ) ) {
			$validated['code_splitting'] = true;
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		if ( isset( $input['enable_caching'] ) ) {
			$sanitized['enable_caching'] = (bool) $input['enable_caching'];
		}

		if ( isset( $input['cache_duration'] ) ) {
			$sanitized['cache_duration'] = (int) $input['cache_duration'];
		}

		if ( isset( $input['code_splitting'] ) ) {
			$sanitized['code_splitting'] = (bool) $input['code_splitting'];
		}

		return $sanitized;
	}
}
