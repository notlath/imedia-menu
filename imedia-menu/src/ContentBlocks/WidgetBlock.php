<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class WidgetBlock implements ContentBlock {

	public function type(): string {
		return 'widget';
	}

	public function title(): string {
		return __( 'Widget Area', 'imedia-menu' );
	}

	public function render( array $config, array $styles = array() ): string {
		$widgetArea = $config['widget_area'] ?? '';

		if ( empty( $widgetArea ) || ! is_active_sidebar( $widgetArea ) ) {
			return sprintf(
				'<div class="imm-block imm-block--widget"><p class="imm-empty">%s</p></div>',
				esc_html__( 'Select a widget area', 'imedia-menu' )
			);
		}

		ob_start();
		dynamic_sidebar( $widgetArea );
		$sidebarHtml = ob_get_clean();

		return sprintf(
			'<div class="imm-block imm-block--widget">%s</div>',
			$sidebarHtml
		);
	}

	public function defaultConfig(): array {
		return array(
			'widget_area' => '',
		);
	}
}
