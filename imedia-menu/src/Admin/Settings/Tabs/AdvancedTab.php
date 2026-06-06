<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;

final class AdvancedTab implements SettingsTab {

	public function id(): string {
		return 'advanced';
	}

	public function label(): string {
		return __( 'Advanced', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Delete Data on Uninstall', 'imedia-menu' ); ?></th>
				<td>
					<label>
						<input type="checkbox"
								name="imedia_menu_settings[delete_data_on_uninstall]"
								value="1"
								<?php checked( $settings['delete_data_on_uninstall'] ?? false ); ?>
						/>
						<?php esc_html_e( 'Remove all plugin data when deleting the plugin', 'imedia-menu' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Export Settings', 'imedia-menu' ); ?></th>
				<td>
					<button type="button"
							class="button imedia-export-btn"
							data-export="settings">
						<?php esc_html_e( 'Download Export JSON', 'imedia-menu' ); ?>
					</button>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Import Settings', 'imedia-menu' ); ?></th>
				<td>
					<input type="file"
							accept=".json"
							class="imedia-import-input"
					/>
					<button type="button"
							class="button imedia-import-btn">
						<?php esc_html_e( 'Import', 'imedia-menu' ); ?>
					</button>
				</td>
			</tr>
		</table>
		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['delete_data_on_uninstall'] ) ) {
			$validated['delete_data_on_uninstall'] = true;
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		$sanitized = array();

		if ( isset( $input['delete_data_on_uninstall'] ) ) {
			$sanitized['delete_data_on_uninstall'] = (bool) $input['delete_data_on_uninstall'];
		}

		return $sanitized;
	}
}
