<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\MenuEditor;

use stdClass;

final class MenuItemFields {

	public function renderFields( string $itemId, object $item, int $depth, ?stdClass $args, int $currentObjectId ): void {
		if ( $depth > 0 && $item->menu_item_parent === '0' ) {
			return;
		}

		$icon           = get_post_meta( $item->ID, '_imedia_menu_icon', true );
		$iconPosition   = get_post_meta( $item->ID, '_imedia_menu_icon_position', true );
		$iconPosition   = ( is_string( $iconPosition ) && $iconPosition !== '' ) ? $iconPosition : 'before';
		$badgeStyle     = get_post_meta( $item->ID, '_imedia_menu_badge_style', true );
		$badgeStyle     = ( is_string( $badgeStyle ) && $badgeStyle !== '' ) ? $badgeStyle : 'disabled';
		$badgeText      = get_post_meta( $item->ID, '_imedia_menu_badge_text', true );
		$badgeColor     = get_post_meta( $item->ID, '_imedia_menu_badge_color', true );
		$badgeColor     = ( is_string( $badgeColor ) && $badgeColor !== '' ) ? $badgeColor : '#e74c3c';
		$badgeTextColor = get_post_meta( $item->ID, '_imedia_menu_badge_text_color', true );
		$badgeTextColor = ( is_string( $badgeTextColor ) && $badgeTextColor !== '' ) ? $badgeTextColor : '#ffffff';
		$badgeHideMob   = get_post_meta( $item->ID, '_imedia_menu_badge_hide_mobile', true ) === 'true';
		$badgeHideDesk  = get_post_meta( $item->ID, '_imedia_menu_badge_hide_desktop', true ) === 'true';
		$description    = get_post_meta( $item->ID, '_imedia_menu_description', true );
		$imageId        = (int) get_post_meta( $item->ID, '_imedia_menu_image_id', true );
		$disableLink    = (bool) get_post_meta( $item->ID, '_imedia_menu_disable_link', true );
		$megaEnabled    = (bool) get_post_meta( $item->ID, '_imedia_menu_mega_enabled', true );
		$stickyVis      = get_post_meta( $item->ID, '_imedia_menu_sticky_visibility', true );
		$stickyVis      = ( is_string( $stickyVis ) && $stickyVis !== '' ) ? $stickyVis : 'always';
		$stylesEnabled  = get_post_meta( $item->ID, '_imedia_menu_styles_enabled', true );
		$stylesValues   = get_post_meta( $item->ID, '_imedia_menu_styles_values', true );
		$stylesEnabled  = is_array( $stylesEnabled ) ? $stylesEnabled : array();
		$stylesValues   = is_array( $stylesValues ) ? $stylesValues : array();

		?>
		<div class="imedia-menu-fields description-wide" style="margin:10px 0;padding:10px;background:#f0f0f1;border-radius:4px;">
			<p style="margin:0 0 8px;font-weight:600;color:#1d2327;">
				<?php esc_html_e( 'iMedia Menu Settings', 'imedia-menu' ); ?>
			</p>

			<p>
				<label>
					<input type="checkbox"
							name="_imedia_menu_mega_enabled[<?php echo esc_attr( (string) $item->ID ); ?>]"
							value="1"
							<?php checked( $megaEnabled ); ?>
							class="imedia-mega-toggle"
							data-item-id="<?php echo esc_attr( (string) $item->ID ); ?>"
					/>
					<?php esc_html_e( 'Enable Mega Menu', 'imedia-menu' ); ?>
				</label>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Icon', 'imedia-menu' ); ?>
				</label>
				<select name="_imedia_menu_icon[<?php echo esc_attr( (string) $item->ID ); ?>]"
						style="width:100%;max-width:200px;">
					<option value=""><?php esc_html_e( 'No icon', 'imedia-menu' ); ?></option>
					<optgroup label="<?php esc_attr_e( 'Dashicons', 'imedia-menu' ); ?>">
						<option value="dashicons:admin-home" <?php selected( $icon, 'dashicons:admin-home' ); ?>>Home</option>
						<option value="dashicons:admin-users" <?php selected( $icon, 'dashicons:admin-users' ); ?>>Users</option>
						<option value="dashicons:admin-settings" <?php selected( $icon, 'dashicons:admin-settings' ); ?>>Settings</option>
						<option value="dashicons:admin-post" <?php selected( $icon, 'dashicons:admin-post' ); ?>>Post</option>
						<option value="dashicons:admin-page" <?php selected( $icon, 'dashicons:admin-page' ); ?>>Page</option>
						<option value="dashicons:search" <?php selected( $icon, 'dashicons:search' ); ?>>Search</option>
						<option value="dashicons:star-filled" <?php selected( $icon, 'dashicons:star-filled' ); ?>>Star</option>
						<option value="dashicons:heart" <?php selected( $icon, 'dashicons:heart' ); ?>>Heart</option>
					</optgroup>
				</select>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Icon Position', 'imedia-menu' ); ?>
				</label>
				<select name="_imedia_menu_icon_position[<?php echo esc_attr( (string) $item->ID ); ?>]">
					<option value="before" <?php selected( $iconPosition, 'before' ); ?>><?php esc_html_e( 'Before Text', 'imedia-menu' ); ?></option>
					<option value="after" <?php selected( $iconPosition, 'after' ); ?>><?php esc_html_e( 'After Text', 'imedia-menu' ); ?></option>
				</select>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Badge', 'imedia-menu' ); ?>
				</label>
				<select name="_imedia_menu_badge_style[<?php echo esc_attr( (string) $item->ID ); ?>]"
						style="width:100%;max-width:200px;">
					<option value="disabled" <?php selected( $badgeStyle, 'disabled' ); ?>><?php esc_html_e( 'Disabled', 'imedia-menu' ); ?></option>
					<option value="style-1" <?php selected( $badgeStyle, 'style-1' ); ?>><?php esc_html_e( 'Style 1 (Red)', 'imedia-menu' ); ?></option>
					<option value="style-2" <?php selected( $badgeStyle, 'style-2' ); ?>><?php esc_html_e( 'Style 2 (Teal)', 'imedia-menu' ); ?></option>
					<option value="style-3" <?php selected( $badgeStyle, 'style-3' ); ?>><?php esc_html_e( 'Style 3 (Amber)', 'imedia-menu' ); ?></option>
					<option value="style-4" <?php selected( $badgeStyle, 'style-4' ); ?>><?php esc_html_e( 'Style 4 (Indigo)', 'imedia-menu' ); ?></option>
				</select>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Badge Text', 'imedia-menu' ); ?>
				</label>
				<input type="text"
						name="_imedia_menu_badge_text[<?php echo esc_attr( (string) $item->ID ); ?>]"
						value="<?php echo esc_attr( $badgeText ); ?>"
						placeholder="<?php esc_attr_e( 'e.g., New, Sale', 'imedia-menu' ); ?>"
						style="width:100%;max-width:200px;"
				/>
			</p>

			<p>
				<label style="margin-right:12px;">
					<input type="checkbox"
							name="_imedia_menu_badge_hide_mobile[<?php echo esc_attr( (string) $item->ID ); ?>]"
							value="true"
							<?php checked( $badgeHideMob ); ?>
					/>
					<?php esc_html_e( 'Hide badge on mobile', 'imedia-menu' ); ?>
				</label>
				<label>
					<input type="checkbox"
							name="_imedia_menu_badge_hide_desktop[<?php echo esc_attr( (string) $item->ID ); ?>]"
							value="true"
							<?php checked( $badgeHideDesk ); ?>
					/>
					<?php esc_html_e( 'Hide badge on desktop', 'imedia-menu' ); ?>
				</label>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Sticky Visibility', 'imedia-menu' ); ?>
				</label>
				<select name="_imedia_menu_sticky_visibility[<?php echo esc_attr( (string) $item->ID ); ?>]"
						style="width:100%;max-width:200px;">
					<option value="always" <?php selected( $stickyVis, 'always' ); ?>><?php esc_html_e( 'Always show', 'imedia-menu' ); ?></option>
					<option value="show-when-stuck" <?php selected( $stickyVis, 'show-when-stuck' ); ?>><?php esc_html_e( 'Show only when stuck', 'imedia-menu' ); ?></option>
					<option value="hide-when-stuck" <?php selected( $stickyVis, 'hide-when-stuck' ); ?>><?php esc_html_e( 'Hide when stuck', 'imedia-menu' ); ?></option>
				</select>
			</p>

			<p>
				<label style="display:block;margin-bottom:4px;">
					<?php esc_html_e( 'Description', 'imedia-menu' ); ?>
				</label>
				<textarea name="_imedia_menu_description[<?php echo esc_attr( (string) $item->ID ); ?>]"
							rows="2"
							style="width:100%;max-width:300px;"><?php echo esc_textarea( $description ); ?></textarea>
			</p>

			<p>
				<label>
					<input type="checkbox"
							name="_imedia_menu_disable_link[<?php echo esc_attr( (string) $item->ID ); ?>]"
							value="1"
							<?php checked( $disableLink ); ?>
					/>
					<?php esc_html_e( 'Disable Link (heading/separator)', 'imedia-menu' ); ?>
				</label>
			</p>

			<p>
				<button type="button"
						class="button imedia-open-builder"
						data-item-id="<?php echo esc_attr( (string) $item->ID ); ?>"
						style="margin-top:4px;">
					<span class="dashicons dashicons-layout" style="margin-top:3px;"></span>
					<?php esc_html_e( 'Open Mega Panel Builder', 'imedia-menu' ); ?>
				</button>
			</p>

			<details style="margin-top:10px;">
				<summary style="font-weight:600;cursor:pointer;color:#1d2327;">
					<?php esc_html_e( 'Per-Item Style Overrides (Pro)', 'imedia-menu' ); ?>
				</summary>
				<?php $this->renderStyleOverrides( $item->ID, $stylesEnabled, $stylesValues ); ?>
			</details>
		</div>
		<?php
	}

	private function renderStyleOverrides( int $itemId, array $enabled, array $values ): void {
		?>
		<p class="description" style="margin:8px 0;">
			<?php esc_html_e( 'Tick the "Override" checkbox to apply a custom style to this menu item only. These values override the global theme.', 'imedia-menu' ); ?>
		</p>
		<table class="widefat imedia-styling-table" style="background:#fff;">
			<thead>
				<tr>
					<th style="width:80px;"><?php esc_html_e( 'Override', 'imedia-menu' ); ?></th>
					<th style="width:200px;"><?php esc_html_e( 'Property', 'imedia-menu' ); ?></th>
					<th><?php esc_html_e( 'Value', 'imedia-menu' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$groups = array(
				__( 'Background', 'imedia-menu' ) => array(
					'menu_item_background_from'       => array(
						'type'  => 'color',
						'label' => __( 'Background From', 'imedia-menu' ),
					),
					'menu_item_background_to'         => array(
						'type'  => 'color',
						'label' => __( 'Background To', 'imedia-menu' ),
					),
					'menu_item_background_hover_from' => array(
						'type'  => 'color',
						'label' => __( 'Hover Background From', 'imedia-menu' ),
					),
					'menu_item_background_hover_to'   => array(
						'type'  => 'color',
						'label' => __( 'Hover Background To', 'imedia-menu' ),
					),
				),
				__( 'Font', 'imedia-menu' )       => array(
					'menu_item_link_color'           => array(
						'type'  => 'color',
						'label' => __( 'Color', 'imedia-menu' ),
					),
					'menu_item_link_color_hover'     => array(
						'type'  => 'color',
						'label' => __( 'Hover Color', 'imedia-menu' ),
					),
					'menu_item_link_weight'          => array(
						'type'    => 'select',
						'label'   => __( 'Weight', 'imedia-menu' ),
						'options' => array(
							'inherit' => 'Theme Default',
							'normal'  => 'Normal (400)',
							'bold'    => 'Bold (700)',
						),
					),
					'menu_item_link_weight_hover'    => array(
						'type'    => 'select',
						'label'   => __( 'Hover Weight', 'imedia-menu' ),
						'options' => array(
							'inherit' => 'Theme Default',
							'normal'  => 'Normal (400)',
							'bold'    => 'Bold (700)',
						),
					),
					'menu_item_font_size'            => array(
						'type'        => 'text',
						'label'       => __( 'Font Size', 'imedia-menu' ),
						'placeholder' => '14px',
					),
					'menu_item_link_text_align'      => array(
						'type'    => 'select',
						'label'   => __( 'Text Align', 'imedia-menu' ),
						'options' => array(
							'left'   => 'Left',
							'center' => 'Center',
							'right'  => 'Right',
						),
					),
					'menu_item_link_text_transform'  => array(
						'type'    => 'select',
						'label'   => __( 'Text Transform', 'imedia-menu' ),
						'options' => array(
							'none'       => 'None',
							'uppercase'  => 'Uppercase',
							'lowercase'  => 'Lowercase',
							'capitalize' => 'Capitalize',
						),
					),
					'menu_item_link_text_decoration' => array(
						'type'    => 'select',
						'label'   => __( 'Text Decoration', 'imedia-menu' ),
						'options' => array(
							'none'      => 'None',
							'underline' => 'Underline',
						),
					),
				),
				__( 'Border', 'imedia-menu' )     => array(
					'menu_item_border_color'               => array(
						'type'  => 'color',
						'label' => __( 'Color', 'imedia-menu' ),
					),
					'menu_item_border_color_hover'         => array(
						'type'  => 'color',
						'label' => __( 'Hover Color', 'imedia-menu' ),
					),
					'menu_item_border_top'                 => array(
						'type'        => 'text',
						'label'       => __( 'Top Width', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_right'               => array(
						'type'        => 'text',
						'label'       => __( 'Right Width', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_bottom'              => array(
						'type'        => 'text',
						'label'       => __( 'Bottom Width', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_left'                => array(
						'type'        => 'text',
						'label'       => __( 'Left Width', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_radius_top_left'     => array(
						'type'        => 'text',
						'label'       => __( 'Radius Top Left', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_radius_top_right'    => array(
						'type'        => 'text',
						'label'       => __( 'Radius Top Right', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_radius_bottom_right' => array(
						'type'        => 'text',
						'label'       => __( 'Radius Bottom Right', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_border_radius_bottom_left'  => array(
						'type'        => 'text',
						'label'       => __( 'Radius Bottom Left', 'imedia-menu' ),
						'placeholder' => '0px',
					),
				),
				__( 'Icon', 'imedia-menu' )       => array(
					'menu_item_icon_size'        => array(
						'type'        => 'text',
						'label'       => __( 'Icon Size', 'imedia-menu' ),
						'placeholder' => '16px',
					),
					'menu_item_icon_color'       => array(
						'type'  => 'color',
						'label' => __( 'Icon Color', 'imedia-menu' ),
					),
					'menu_item_icon_color_hover' => array(
						'type'  => 'color',
						'label' => __( 'Icon Hover Color', 'imedia-menu' ),
					),
				),
				__( 'Spacing', 'imedia-menu' )    => array(
					'menu_item_padding_left'   => array(
						'type'        => 'text',
						'label'       => __( 'Padding Left', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_padding_right'  => array(
						'type'        => 'text',
						'label'       => __( 'Padding Right', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_padding_top'    => array(
						'type'        => 'text',
						'label'       => __( 'Padding Top', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_padding_bottom' => array(
						'type'        => 'text',
						'label'       => __( 'Padding Bottom', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_margin_left'    => array(
						'type'        => 'text',
						'label'       => __( 'Margin Left', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_margin_right'   => array(
						'type'        => 'text',
						'label'       => __( 'Margin Right', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_margin_top'     => array(
						'type'        => 'text',
						'label'       => __( 'Margin Top', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_margin_bottom'  => array(
						'type'        => 'text',
						'label'       => __( 'Margin Bottom', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'menu_item_height'         => array(
						'type'        => 'text',
						'label'       => __( 'Height', 'imedia-menu' ),
						'placeholder' => '60px',
					),
				),
				__( 'Sub-menu (Mega Panel)', 'imedia-menu' ) => array(
					'panel_width'             => array(
						'type'        => 'text',
						'label'       => __( 'Panel Width', 'imedia-menu' ),
						'placeholder' => '800px',
					),
					'panel_horizontal_offset' => array(
						'type'        => 'text',
						'label'       => __( 'Horizontal Offset', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'panel_vertical_offset'   => array(
						'type'        => 'text',
						'label'       => __( 'Vertical Offset', 'imedia-menu' ),
						'placeholder' => '0px',
					),
					'panel_background_from'   => array(
						'type'  => 'color',
						'label' => __( 'Panel Background From', 'imedia-menu' ),
					),
					'panel_background_to'     => array(
						'type'  => 'color',
						'label' => __( 'Panel Background To', 'imedia-menu' ),
					),
				),
			);

			foreach ( $groups as $groupLabel => $properties ) :
				?>
				<tr>
					<td colspan="3" style="background:#f0f0f1;font-weight:600;padding:6px 10px;">
						<?php echo esc_html( $groupLabel ); ?>
					</td>
				</tr>
				<?php
				foreach ( $properties as $propKey => $propConfig ) :
					$isOn  = in_array( $propKey, $enabled, true );
					$value = $values[ $propKey ] ?? '';
					?>
					<tr>
						<td>
							<input type="checkbox"
									name="_imedia_menu_styles_enabled[<?php echo esc_attr( (string) $itemId ); ?>][]"
									value="<?php echo esc_attr( $propKey ); ?>"
									<?php checked( $isOn ); ?>
							/>
						</td>
						<td><?php echo esc_html( $propConfig['label'] ); ?></td>
						<td>
							<?php if ( 'color' === $propConfig['type'] ) : ?>
								<input type="text"
										class="imedia-color-picker"
										name="_imedia_menu_styles_values[<?php echo esc_attr( (string) $itemId ); ?>][<?php echo esc_attr( $propKey ); ?>]"
										value="<?php echo esc_attr( $value ); ?>"
										placeholder="#ffffff"
								/>
							<?php elseif ( 'select' === $propConfig['type'] ) : ?>
								<select name="_imedia_menu_styles_values[<?php echo esc_attr( (string) $itemId ); ?>][<?php echo esc_attr( $propKey ); ?>]">
									<?php foreach ( $propConfig['options'] as $optVal => $optLabel ) : ?>
										<option value="<?php echo esc_attr( $optVal ); ?>" <?php selected( $value, $optVal ); ?>>
											<?php echo esc_html( $optLabel ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							<?php else : ?>
								<input type="text"
										name="_imedia_menu_styles_values[<?php echo esc_attr( (string) $itemId ); ?>][<?php echo esc_attr( $propKey ); ?>]"
										value="<?php echo esc_attr( $value ); ?>"
										placeholder="<?php echo esc_attr( $propConfig['placeholder'] ?? '' ); ?>"
								/>
							<?php endif; ?>
						</td>
					</tr>
					<?php
				endforeach;
			endforeach;
			?>
			</tbody>
		</table>
		<?php
	}

	public function saveFields( int $menuId, int $menuItemDbId, array $menuItemData ): void {
		$fields = array(
			'_imedia_menu_mega_enabled',
			'_imedia_menu_icon',
			'_imedia_menu_icon_position',
			'_imedia_menu_badge_style',
			'_imedia_menu_badge_text',
			'_imedia_menu_badge_color',
			'_imedia_menu_badge_text_color',
			'_imedia_menu_badge_hide_mobile',
			'_imedia_menu_badge_hide_desktop',
			'_imedia_menu_sticky_visibility',
			'_imedia_menu_description',
			'_imedia_menu_image_id',
			'_imedia_menu_disable_link',
		);

		foreach ( $fields as $field ) {
			$key = $field;

            // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$value = wp_unslash( $_POST[ $key ][ $menuItemDbId ] ?? '' );

			if ( $value !== '' ) {
				if ( str_contains( $key, '_color' ) ) {
					$cleaned = sanitize_hex_color( $value );
					update_post_meta( $menuItemDbId, $key, $cleaned ? $cleaned : '' );
				} elseif ( str_contains( $key, '_description' ) ) {
					update_post_meta( $menuItemDbId, $key, sanitize_textarea_field( $value ) );
				} elseif ( str_contains( $key, '_mega_enabled' ) || str_contains( $key, '_disable_link' ) ) {
					update_post_meta( $menuItemDbId, $key, (bool) $value );
				} elseif ( $key === '_imedia_menu_sticky_visibility' ) {
					$allowed = array( 'always', 'show-when-stuck', 'hide-when-stuck' );
					update_post_meta( $menuItemDbId, $key, in_array( $value, $allowed, true ) ? $value : 'always' );
				} elseif ( $key === '_imedia_menu_badge_style' ) {
					$allowed = array( 'disabled', 'style-1', 'style-2', 'style-3', 'style-4' );
					update_post_meta( $menuItemDbId, $key, in_array( $value, $allowed, true ) ? $value : 'disabled' );
				} elseif ( $key === '_imedia_menu_badge_hide_mobile' || $key === '_imedia_menu_badge_hide_desktop' ) {
					update_post_meta( $menuItemDbId, $key, $value === 'true' ? 'true' : 'false' );
				} else {
					update_post_meta( $menuItemDbId, $key, sanitize_text_field( $value ) );
				}
			} else {
				delete_post_meta( $menuItemDbId, $key );
			}
		}

		$this->saveStyleOverrides( $menuItemDbId );
	}

	private function saveStyleOverrides( int $menuItemDbId ): void {
		$rawEnabled = array();
		$rawValues  = array();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$rawEnabledPost = $_POST['_imedia_menu_styles_enabled'][ $menuItemDbId ] ?? array();
		if ( is_array( $rawEnabledPost ) ) {
			$rawEnabled = wp_unslash( $rawEnabledPost );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$rawValuesPost = $_POST['_imedia_menu_styles_values'][ $menuItemDbId ] ?? array();
		if ( is_array( $rawValuesPost ) ) {
			$rawValues = wp_unslash( $rawValuesPost );
		}

		$allowed = array_keys( \IMedia\Menu\Frontend\StyleOverrides::PROPERTIES );
		$enabled = is_array( $rawEnabled ) ? array_values( array_intersect( $rawEnabled, $allowed ) ) : array();
		$values  = is_array( $rawValues ) ? $rawValues : array();

		$cleaned = array();
		foreach ( $values as $prop => $value ) {
			if ( ! in_array( $prop, $allowed, true ) ) {
				continue;
			}
			$sanitized = \IMedia\Menu\Frontend\StyleOverrides::sanitizeValue( $prop, (string) $value );
			if ( $sanitized !== '' ) {
				$cleaned[ $prop ] = $sanitized;
			}
		}

		if ( empty( $enabled ) ) {
			delete_post_meta( $menuItemDbId, '_imedia_menu_styles_enabled' );
		} else {
			update_post_meta( $menuItemDbId, '_imedia_menu_styles_enabled', $enabled );
		}

		if ( empty( $cleaned ) ) {
			delete_post_meta( $menuItemDbId, '_imedia_menu_styles_values' );
		} else {
			update_post_meta( $menuItemDbId, '_imedia_menu_styles_values', $cleaned );
		}
	}

	public function addColumns( array $columns ): array {
		$columns['imedia_menu_mega'] = __( 'Mega Menu', 'imedia-menu' );
		$columns['imedia_menu_icon'] = __( 'Icon', 'imedia-menu' );

		return $columns;
	}
}
