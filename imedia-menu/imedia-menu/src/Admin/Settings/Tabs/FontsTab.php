<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings\Tabs;

use IMedia\Menu\Admin\Settings\Contracts\SettingsTab;
use IMedia\Menu\Fonts\GoogleFontsProvider;

final class FontsTab implements SettingsTab {

	public function id(): string {
		return 'fonts';
	}

	public function label(): string {
		return __( 'Fonts', 'imedia-menu' );
	}

	public function render( array $settings ): void {
		$allFonts   = GoogleFontsProvider::getFonts();
		$allWeights = GoogleFontsProvider::getWeights();
		$allSubsets = GoogleFontsProvider::getSubsets();
		$enabled    = $settings['google_fonts'] ?? array();
		?>
		<h2><?php esc_html_e( 'Google Fonts', 'imedia-menu' ); ?></h2>
		<p><?php esc_html_e( 'Select Google Fonts to load on your site. Each font can have specific weights and subsets configured.', 'imedia-menu' ); ?></p>

		<div id="imm-google-fonts-list">
			<?php if ( empty( $enabled ) || ! is_array( $enabled ) ) : ?>
				<p><em><?php esc_html_e( 'No Google Fonts configured yet. Use the form below to add one.', 'imedia-menu' ); ?></em></p>
			<?php else : ?>
				<?php foreach ( $enabled as $fontName => $config ) : ?>
					<div class="imm-font-row" style="margin-bottom:12px;padding:10px;background:#f0f0f1;border-radius:4px;">
						<strong><?php echo esc_html( $fontName ); ?></strong>
						<input type="hidden" name="imedia_menu_settings[google_fonts][<?php echo esc_attr( $fontName ); ?>][active]" value="1" />
						<div style="margin-top:8px;">
							<label><?php esc_html_e( 'Weights:', 'imedia-menu' ); ?>
								<select name="imedia_menu_settings[google_fonts][<?php echo esc_attr( $fontName ); ?>][weights][]" multiple style="min-width:200px;min-height:80px;">
									<?php foreach ( $allWeights as $w ) : ?>
										<option value="<?php echo esc_attr( (string) $w ); ?>"
											<?php selected( in_array( $w, $config['weights'] ?? array( 400 ), true ) ); ?>>
											<?php echo esc_html( (string) $w ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</label>
						</div>
						<div style="margin-top:4px;">
							<a href="#" class="imm-remove-font" data-font="<?php echo esc_attr( $fontName ); ?>"
								style="color:#b32d2e;text-decoration:none;font-size:12px;">
								<?php esc_html_e( 'Remove this font', 'imedia-menu' ); ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div id="imm-add-font" style="margin-top:20px;padding:15px;background:#f6f7f7;border:1px solid #c3c4c7;border-radius:4px;">
			<h3><?php esc_html_e( 'Add a Google Font', 'imedia-menu' ); ?></h3>
			<p>
				<label><?php esc_html_e( 'Select Font:', 'imedia-menu' ); ?><br>
					<select id="imm-font-select" style="min-width:300px;">
						<option value=""><?php esc_html_e( '— Choose a font —', 'imedia-menu' ); ?></option>
						<?php foreach ( $allFonts as $font ) : ?>
							<option value="<?php echo esc_attr( $font ); ?>" <?php disabled( isset( $enabled[ $font ] ) ); ?>>
								<?php echo esc_html( $font ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<p>
				<label><?php esc_html_e( 'Weights (Ctrl+click to select multiple):', 'imedia-menu' ); ?><br>
					<select id="imm-font-weights" multiple style="min-width:200px;min-height:80px;">
						<?php foreach ( $allWeights as $w ) : ?>
							<option value="<?php echo esc_attr( (string) $w ); ?>" <?php selected( $w === 400 || $w === 700 ); ?>>
								<?php echo esc_html( (string) $w ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<p>
				<button type="button" class="button" id="imm-add-font-btn"><?php esc_html_e( 'Add Font', 'imedia-menu' ); ?></button>
			</p>
			<p class="description"><?php esc_html_e( 'Selected fonts will appear in the list above after saving.', 'imedia-menu' ); ?></p>
		</div>

		<h2><?php esc_html_e( 'Menu Font Settings', 'imedia-menu' ); ?></h2>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e( 'Menu Font Family', 'imedia-menu' ); ?></th>
				<td>
					<select name="imedia_menu_settings[font_family]" style="min-width:300px;">
						<option value="" <?php selected( empty( $settings['font_family'] ) ); ?>><?php esc_html_e( '— Inherit (default) —', 'imedia-menu' ); ?></option>
						<?php foreach ( $enabled as $fontName => $config ) : ?>
							<option value="<?php echo esc_attr( $fontName ); ?>" <?php selected( $settings['font_family'] ?? '', $fontName ); ?>>
								<?php echo esc_html( $fontName ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php esc_html_e( 'Choose one of the enabled Google Fonts to use as the menu font. Select "Inherit" to use your theme default.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Menu Font Size', 'imedia-menu' ); ?></th>
				<td>
					<input type="number"
							name="imedia_menu_settings[font_size]"
							value="<?php echo esc_attr( $settings['font_size'] ?? 15 ); ?>"
							min="10"
							max="50"
							step="1"
							class="small-text"
					/> px
					<p class="description"><?php esc_html_e( 'Font size for menu items (10–50px). Default: 15px.', 'imedia-menu' ); ?></p>
				</td>
			</tr>
		</table>

		<script>
		jQuery( function( $ ) {
			$( '#imm-add-font-btn' ).on( 'click', function() {
				var $select = $( '#imm-font-select' );
				var font = $select.val();
				if ( ! font ) { return; }

				var $weights = $( '#imm-font-weights' );
				var weights = $weights.val() || [ '400' ];

				var $list = $( '#imm-google-fonts-list' );

				var html = '<div class="imm-font-row" style="margin-bottom:12px;padding:10px;background:#f0f0f1;border-radius:4px;">' +
					'<strong>' + font + '</strong>' +
					'<input type="hidden" name="imedia_menu_settings[google_fonts][' + font + '][active]" value="1" />' +
					'<div style="margin-top:8px;">' +
					'<label><?php echo esc_js( __( 'Weights:', 'imedia-menu' ) ); ?> ' +
					'<select name="imedia_menu_settings[google_fonts][' + font + '][weights][]" multiple style="min-width:200px;min-height:80px;">';

				<?php foreach ( $allWeights as $w ) : ?>
				html += '<option value="<?php echo esc_js( (string) $w ); ?>"' + ( weights.indexOf( '<?php echo esc_js( (string) $w ); ?>' ) > -1 ? ' selected' : '' ) + '><?php echo esc_js( (string) $w ); ?></option>';
				<?php endforeach; ?>

				html += '</select></label></div>' +
					'<div style="margin-top:4px;">' +
					'<a href="#" class="imm-remove-font" data-font="' + font + '" style="color:#b32d2e;text-decoration:none;font-size:12px;"><?php echo esc_js( __( 'Remove this font', 'imedia-menu' ) ); ?></a>' +
					'</div></div>';

				if ( $list.find( 'p em' ).length ) {
					$list.empty();
				}

				$list.append( html );
				$select.find( 'option[value="' + font + '"]' ).prop( 'disabled', true );
				$select.val( '' );
			} );

			$( document ).on( 'click', '.imm-remove-font', function( e ) {
				e.preventDefault();
				var font = $( this ).data( 'font' );
				$( this ).closest( '.imm-font-row' ).remove();
				$( '#imm-font-select option[value="' + font + '"]' ).prop( 'disabled', false );

				if ( ! $( '#imm-google-fonts-list .imm-font-row' ).length ) {
					$( '#imm-google-fonts-list' ).html( '<p><em><?php echo esc_js( __( 'No Google Fonts configured yet. Use the form below to add one.', 'imedia-menu' ) ); ?></em></p>' );
				}
			} );
		} );
		</script>

		<?php
	}

	public function validate( array $input ): array {
		$validated = array();

		if ( isset( $input['font_size'] ) ) {
			$validated['font_size'] = min( 50, max( 10, (int) $input['font_size'] ) );
		}

		if ( isset( $input['google_fonts'] ) && is_array( $input['google_fonts'] ) ) {
			$allFonts   = GoogleFontsProvider::getFonts();
			$allWeights = GoogleFontsProvider::getWeights();
			$fontData   = array();

			foreach ( $input['google_fonts'] as $font => $config ) {
				if ( ! in_array( $font, $allFonts, true ) ) {
					continue;
				}

				$weights    = array();
				$rawWeights = $config['weights'] ?? array();

				if ( is_array( $rawWeights ) ) {
					foreach ( $rawWeights as $w ) {
						$weight = (int) $w;
						if ( in_array( $weight, $allWeights, true ) ) {
							$weights[] = $weight;
						}
					}
				}

				if ( isset( $config['active'] ) ) {
					$fontData[ $font ] = array(
						'weights' => $weights,
					);
				}
			}

			if ( ! empty( $fontData ) ) {
				$validated['google_fonts'] = $fontData;

				// Validate font_family against the submitted enabled fonts, not the master list.
				if ( isset( $input['font_family'] ) && $input['font_family'] !== '' ) {
					if ( isset( $fontData[ $input['font_family'] ] ) ) {
						$validated['font_family'] = $input['font_family'];
					}
				}
			}
		}

		return $validated;
	}

	public function sanitize( ?array $input ): array {
		$sanitized = array();

		if ( isset( $input['font_family'] ) ) {
			$sanitized['font_family'] = sanitize_text_field( $input['font_family'] );
		}

		if ( isset( $input['font_size'] ) ) {
			$sanitized['font_size'] = (int) $input['font_size'];
		}

		if ( isset( $input['google_fonts'] ) && is_array( $input['google_fonts'] ) ) {
			$fontData = array();

			foreach ( $input['google_fonts'] as $font => $config ) {
				$weights    = array();
				$rawWeights = $config['weights'] ?? array();

				if ( is_array( $rawWeights ) ) {
					foreach ( $rawWeights as $w ) {
						$weights[] = (int) $w;
					}
				}

				if ( isset( $config['active'] ) ) {
					$fontData[ $font ] = array(
						'weights' => $weights,
					);
				}
			}

			if ( ! empty( $fontData ) ) {
				$sanitized['google_fonts'] = $fontData;
			}
		}

		return $sanitized;
	}
}
