<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Elementor;

use IMedia\Menu\Contracts\ServiceProvider;

final class ElementorIntegration implements ServiceProvider {

	public function register(): void {}

	public function boot(): void {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}

		add_action( 'elementor/widgets/register', array( $this, 'registerWidget' ) );
		add_action( 'widgets_init', array( $this, 'registerTemplateWidget' ) );
	}

	public function registerWidget( \Elementor\Widgets_Manager $widgetsManager ): void {
		$widgetsManager->register( new Widgets\MenuLocationWidget() );
	}

	public function registerTemplateWidget(): void {
		register_widget( Widgets\ElementorTemplateWidget::class );
	}
}
