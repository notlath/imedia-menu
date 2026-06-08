<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class BannerBlock implements ContentBlock {

	public function type(): string {
		return 'banner';
	}

	public function title(): string {
		return __( 'Banner / CTA', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$imageId        = (int) ( $config['image_id'] ?? 0 );
		$title          = $config['title'] ?? '';
		$text           = $config['text'] ?? '';
		$link           = $config['link'] ?? '';
		$buttonText     = $config['button_text'] ?? '';
		$alt            = $config['alt'] ?? '';
		$template       = in_array( $config['template'] ?? 'overlay', array( 'overlay', 'card', 'side' ), true )
			? ( $config['template'] ?? 'overlay' )
			: 'overlay';
		$overlayColor   = (string) ( $config['overlay_color'] ?? '' );
		$overlayOpacity = isset( $config['overlay_opacity'] ) ? max( 0.0, min( 1.0, (float) $config['overlay_opacity'] ) ) : 0.0;
		$ctas           = isset( $config['cta'] ) && is_array( $config['cta'] ) ? $config['cta'] : array();
		$aspectRatio    = (string) ( $config['aspect_ratio'] ?? '' );

		$styleAttr = '';
		if ( ! empty( $styles ) ) {
			$css = array();
			if ( isset( $styles['borderRadius'] ) ) {
				$css[] = 'border-radius:' . $styles['borderRadius'];
			}
			if ( ! empty( $css ) ) {
				$styleAttr = ' style="' . esc_attr( implode( ';', $css ) ) . '"';
			}
		}

		if ( $aspectRatio !== '' ) {
			$styleAttr .= sprintf( ' style="--imm-banner-aspect:%s"', esc_attr( $aspectRatio ) );
		}

		$html = sprintf(
			'<div class="imm-block imm-block--banner imm-block--banner--%s"%s>',
			esc_attr( $template ),
			$styleAttr
		);

		$wrapWithAnchor = $link !== '' && empty( $ctas );
		if ( $wrapWithAnchor ) {
			$html .= sprintf( '<a href="%s" class="imm-banner-link">', esc_url( $link ) );
		}

		if ( $imageId ) {
			$html .= '<div class="imm-banner__media">';
			$html .= wp_get_attachment_image(
				$imageId,
				'large',
				false,
				array(
					'class'   => 'imm-banner-image',
					'alt'     => $alt ? $alt : '',
					'loading' => 'lazy',
				)
			);
			if ( $template === 'overlay' && $overlayColor !== '' ) {
				$html .= sprintf(
					'<div class="imm-banner__overlay" style="background-color:%s;opacity:%s"></div>',
					esc_attr( $overlayColor ),
					esc_attr( (string) $overlayOpacity )
				);
			}
			$html .= '</div>';
		}

		if ( $title || $text || $buttonText || ! empty( $ctas ) ) {
			$html .= '<div class="imm-banner-content">';

			if ( $title ) {
				$html .= sprintf(
					'<h4 class="imm-banner-title">%s</h4>',
					esc_html( $title )
				);
			}

			if ( $text ) {
				$html .= sprintf(
					'<p class="imm-banner-text">%s</p>',
					esc_html( $text )
				);
			}

			if ( $buttonText ) {
				$html .= sprintf(
					'<span class="imm-banner-button">%s</span>',
					esc_html( $buttonText )
				);
			}

			if ( ! empty( $ctas ) ) {
				$html .= '<div class="imm-banner-ctas">';
				foreach ( $ctas as $cta ) {
					if ( ! is_array( $cta ) ) {
						continue;
					}
					$ctaLabel  = (string) ( $cta['label'] ?? '' );
					$ctaUrl    = (string) ( $cta['url'] ?? '' );
					$ctaTarget = in_array( $cta['target'] ?? '', array( '_self', '_blank' ), true ) ? $cta['target'] : '_self';
					$ctaStyle  = (string) ( $cta['style'] ?? 'primary' );
					if ( $ctaLabel === '' || $ctaUrl === '' ) {
						continue;
					}
					$html .= sprintf(
						'<a href="%s" target="%s" rel="%s" class="imm-banner-cta imm-banner-cta--%s">%s</a>',
						esc_url( $ctaUrl ),
						esc_attr( $ctaTarget ),
						$ctaTarget === '_blank' ? 'noopener noreferrer' : '',
						esc_attr( $ctaStyle ),
						esc_html( $ctaLabel )
					);
				}
				$html .= '</div>';
			}

			$html .= '</div>';
		}

		if ( $wrapWithAnchor ) {
			$html .= '</a>';
		}

		$html .= '</div>';

		return $html;
	}

	public function defaultConfig(): array {
		return array(
			'image_id'        => 0,
			'title'           => '',
			'text'            => '',
			'link'            => '',
			'button_text'     => '',
			'alt'             => '',
			'template'        => 'overlay',
			'overlay_color'   => '',
			'overlay_opacity' => 0.0,
			'cta'             => array(),
			'aspect_ratio'    => '',
		);
	}
}
