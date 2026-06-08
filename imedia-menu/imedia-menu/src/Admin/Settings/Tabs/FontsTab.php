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

		if ( isset( $input['google_fonts'] ) && is_array( $input['google_fonts'] ) ) {
			$allFonts   = GoogleFontsProvider::getFonts();
			$allWeights = GoogleFontsProvider::getWeights();

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
					$validated[ $font ] = array(
						'weights' => $weights,
					);
				}
			}
		}

		return $validated;
	}

	public function sanitize( array $input ): array {
		return $this->validate( $input );
	}
}
