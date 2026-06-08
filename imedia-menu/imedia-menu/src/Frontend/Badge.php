<?php

declare(strict_types=1);

namespace IMedia\Menu\Frontend;

final class Badge {

	public const STYLES = array( 'disabled', 'style-1', 'style-2', 'style-3', 'style-4' );

	public static function render( int $itemId ): string {
		$style = get_post_meta( $itemId, '_imedia_menu_badge_style', true ) ?: 'disabled';
		if ( ! in_array( $style, self::STYLES, true ) || $style === 'disabled' ) {
			return '';
		}

		$text = get_post_meta( $itemId, '_imedia_menu_badge_text', true );
		if ( $text === '' ) {
			return '';
		}

		$classes   = array( 'imm-badge', 'imm-badge--' . $style );
		$hideMob   = get_post_meta( $itemId, '_imedia_menu_badge_hide_mobile', true ) === 'true';
		$hideDesk  = get_post_meta( $itemId, '_imedia_menu_badge_hide_desktop', true ) === 'true';
		$bgColor   = get_post_meta( $itemId, '_imedia_menu_badge_color', true ) ?: '';
		$textColor = get_post_meta( $itemId, '_imedia_menu_badge_text_color', true ) ?: '';

		if ( $hideMob ) {
			$classes[] = 'imm-hide-on-mobile';
		}
		if ( $hideDesk ) {
			$classes[] = 'imm-hide-on-desktop';
		}

		$styleAttr = '';
		if ( $bgColor !== '' ) {
			$styleAttr .= '--imm-badge-bg:' . sanitize_text_field( $bgColor ) . ';';
		}
		if ( $textColor !== '' ) {
			$styleAttr .= '--imm-badge-text:' . sanitize_text_field( $textColor ) . ';';
		}

		$styleString = $styleAttr !== '' ? ' style="' . esc_attr( $styleAttr ) . '"' : '';

		return sprintf(
			'<span class="%s" data-style="%s"%s>%s</span>',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $style ),
			$styleString,
			esc_html( do_shortcode( $text ) )
		);
	}
}
