<?php

declare(strict_types=1);

namespace IMedia\Menu\Integrations\Elementor\Widgets;

use WP_Widget;

final class ElementorTemplateWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'imedia_menu_elementor_template',
			__( 'iMedia Menu — Elementor Template', 'imedia-menu' ),
			array(
				'description' => __( 'Display an Elementor template inside a mega menu panel.', 'imedia-menu' ),
			)
		);
	}

	public function widget( $args, $instance ): void {
		$templateId = $instance['template_id'] ?? 0;
		if ( empty( $templateId ) ) {
			return;
		}
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( absint( $templateId ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ): void {
		$templateId = $instance['template_id'] ?? 0;
		$templates  = $this->getElementorTemplates();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'template_id' ) ); ?>">
				<?php esc_html_e( 'Select Elementor Template:', 'imedia-menu' ); ?>
			</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'template_id' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'template_id' ) ); ?>"
				class="widefat">
				<option value=""><?php esc_html_e( '— Select —', 'imedia-menu' ); ?></option>
				<?php foreach ( $templates as $id => $title ) : ?>
					<option value="<?php echo esc_attr( (string) $id ); ?>" <?php selected( $templateId, $id ); ?>>
						<?php echo esc_html( $title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ): array {
		$instance                = array();
		$instance['template_id'] = absint( $new_instance['template_id'] ?? 0 );
		return $instance;
	}

	private function getElementorTemplates(): array {
		$templates = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$options = array();
		foreach ( $templates as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}
		return $options;
	}
}
