<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class RealWidgetBlock implements ContentBlock {

	public function type(): string {
		return 'real_widget';
	}

	public function title(): string {
		return __( 'Real Widget', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'widget_class'  => '',
			'instance'      => array(),
			'title'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$widgetClass = isset( $config['widget_class'] ) ? (string) $config['widget_class'] : '';

		if ( $widgetClass === '' || ! class_exists( $widgetClass ) ) {
			return $this->renderUnavailable( $widgetClass );
		}

		if ( ! is_subclass_of( $widgetClass, '\\WP_Widget' ) && $widgetClass !== '\\WP_Widget' ) {
			return $this->renderUnavailable( $widgetClass );
		}

		$instance = is_array( $config['instance'] ?? null ) ? $config['instance'] : array();

		$args = array(
			'before_widget' => $config['before_widget'] ?? '<div class="widget %1$s %2$s">',
			'after_widget'  => $config['after_widget'] ?? '</div>',
			'before_title'  => $config['before_title'] ?? '<h2 class="widgettitle">',
			'after_title'   => $config['after_title'] ?? '</h2>',
		);

		if ( ! empty( $config['title'] ) ) {
			$instance['title'] = (string) $config['title'];
		}

		ob_start();
		the_widget( $widgetClass, $instance, $args );
		$widgetHtml = (string) ob_get_clean();

		if ( $widgetHtml === '' ) {
			return $this->renderUnavailable( $widgetClass );
		}

		return sprintf(
			'<div class="imm-block imm-block--real-widget" data-widget-class="%s">%s</div>',
			esc_attr( $widgetClass ),
			$widgetHtml
		);
	}

	private function renderUnavailable( string $widgetClass ): string {
		$label = $widgetClass !== '' ? $widgetClass : __( '(no widget selected)', 'imedia-menu' );

		return sprintf(
			'<div class="imm-block imm-block--real-widget imm-block--unavailable"><p class="imm-empty">%s: %s</p></div>',
			esc_html__( 'Real widget unavailable', 'imedia-menu' ),
			esc_html( $label )
		);
	}
}
