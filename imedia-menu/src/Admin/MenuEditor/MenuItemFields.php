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
		$iconPosition   = get_post_meta( $item->ID, '_imedia_menu_icon_position', true ) ?: 'before';
		$badgeText      = get_post_meta( $item->ID, '_imedia_menu_badge_text', true );
		$badgeColor     = get_post_meta( $item->ID, '_imedia_menu_badge_color', true ) ?: '#e74c3c';
		$badgeTextColor = get_post_meta( $item->ID, '_imedia_menu_badge_text_color', true ) ?: '#ffffff';
		$badgePosition  = get_post_meta( $item->ID, '_imedia_menu_badge_position', true ) ?: 'inline';
		$description    = get_post_meta( $item->ID, '_imedia_menu_description', true );
		$imageId        = (int) get_post_meta( $item->ID, '_imedia_menu_image_id', true );
		$disableLink    = (bool) get_post_meta( $item->ID, '_imedia_menu_disable_link', true );
		$megaEnabled    = (bool) get_post_meta( $item->ID, '_imedia_menu_mega_enabled', true );

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
		</div>
		<?php
	}

	public function saveFields( int $menuId, int $menuItemDbId, array $menuItemData ): void {
		$fields = array(
			'_imedia_menu_mega_enabled',
			'_imedia_menu_icon',
			'_imedia_menu_icon_position',
			'_imedia_menu_badge_text',
			'_imedia_menu_badge_color',
			'_imedia_menu_badge_text_color',
			'_imedia_menu_badge_position',
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
					update_post_meta( $menuItemDbId, $key, sanitize_hex_color( $value ) ?: '' );
				} elseif ( str_contains( $key, '_description' ) ) {
					update_post_meta( $menuItemDbId, $key, sanitize_textarea_field( $value ) );
				} elseif ( str_contains( $key, '_mega_enabled' ) || str_contains( $key, '_disable_link' ) ) {
					update_post_meta( $menuItemDbId, $key, (bool) $value );
				} else {
					update_post_meta( $menuItemDbId, $key, sanitize_text_field( $value ) );
				}
			} else {
				delete_post_meta( $menuItemDbId, $key );
			}
		}
	}

	public function addColumns( array $columns ): array {
		$columns['imedia_menu_mega'] = __( 'Mega Menu', 'imedia-menu' );
		$columns['imedia_menu_icon'] = __( 'Icon', 'imedia-menu' );

		return $columns;
	}
}
