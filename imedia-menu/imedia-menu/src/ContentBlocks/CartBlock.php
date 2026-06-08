<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class CartBlock implements ContentBlock {

	public function type(): string {
		return 'cart';
	}

	public function title(): string {
		return __( 'Cart', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'display'         => 'icon',
			'show_count'      => true,
			'show_total'      => false,
			'show_thumbnails' => false,
			'empty_text'      => '',
			'cart_url'        => '',
			'hide_when_empty' => false,
			'icon'            => 'dashicons-cart',
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$display = $config['display'] ?? 'icon';

		if ( ! function_exists( 'WC' ) ) {
			return $this->renderUnavailable( $display, $config );
		}

		$wc = WC();
		if ( $wc === null || ! isset( $wc->cart ) ) {
			return $this->renderUnavailable( $display, $config );
		}

		$cart  = $wc->cart;
		$count = $cart ? (int) $cart->get_cart_contents_count() : 0;
		$total = $cart ? (string) $cart->get_cart_subtotal() : '';

		if ( $count === 0 && ! empty( $config['hide_when_empty'] ) ) {
			return '';
		}

		$cartUrl = ! empty( $config['cart_url'] ) ? (string) $config['cart_url'] : ( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '' );

		$html = sprintf(
			'<div class="imm-block imm-block--cart imm-block--cart--%s" data-count="%d">',
			esc_attr( $display ),
			$count
		);

		$icon  = ! empty( $config['icon'] ) ? (string) $config['icon'] : 'dashicons-cart';
		$html .= sprintf( '<span class="imm-cart__icon %s" aria-hidden="true"></span>', esc_attr( $icon ) );

		if ( $count === 0 ) {
			$emptyText = (string) ( $config['empty_text'] ?? '' );
			$html     .= sprintf( '<span class="imm-cart__empty">%s</span>', esc_html( $emptyText ) );
		} else {
			if ( ! empty( $config['show_count'] ) ) {
				$html .= sprintf( '<span class="imm-cart__count">%d</span>', $count );
			}
			if ( ! empty( $config['show_total'] ) ) {
				$html .= sprintf( '<span class="imm-cart__total">%s</span>', esc_html( $total ) );
			}
		}

		if ( $cartUrl !== '' ) {
			$html = sprintf( '<a href="%s" class="imm-cart__link">', esc_url( $cartUrl ) ) . $html . '</a>';
		}

		if ( $count > 0 && in_array( $display, array( 'mini', 'full' ), true ) && $cart ) {
			$items = $cart->get_cart();
			if ( is_array( $items ) && ! empty( $items ) ) {
				$html .= '<ul class="imm-cart__items">';
				$limit = $display === 'mini' ? min( 3, count( $items ) ) : count( $items );
				$i     = 0;
				foreach ( $items as $item ) {
					if ( $i >= $limit ) {
						break;
					}
					++$i;
					$name = isset( $item['data']->get_name ) || ( is_object( $item['data'] ) && method_exists( $item['data'], 'get_name' ) )
						? (string) $item['data']->get_name()
						: __( 'Item', 'imedia-menu' );
					$qty  = isset( $item['quantity'] ) ? (int) $item['quantity'] : 1;
					$line = sprintf( '%s × %d', $name, $qty );
					if ( ! empty( $config['show_thumbnails'] ) && method_exists( $item['data'], 'get_image' ) ) {
						$line = (string) $item['data']->get_image() . ' ' . $line;
					}
					$html .= sprintf( '<li class="imm-cart__item">%s</li>', $line );
				}
				$html .= '</ul>';
			}
		}

		$html .= '</div>';

		return $html;
	}

	private function renderUnavailable( string $display, array $config ): string {
		if ( ! empty( $config['hide_when_empty'] ) ) {
			return '';
		}
		$emptyText = (string) ( $config['empty_text'] ?? '' );
		if ( $emptyText === '' ) {
			$emptyText = __( 'Cart unavailable', 'imedia-menu' );
		}
		return sprintf(
			'<div class="imm-block imm-block--cart imm-block--cart--%s imm-block--unavailable"><span class="imm-cart__empty">%s</span></div>',
			esc_attr( $display ),
			esc_html( $emptyText )
		);
	}
}
