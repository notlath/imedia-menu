<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class SettingsPage {

	private SettingsRegistry $registry;

	public function __construct( ?SettingsRegistry $registry = null ) {
		$this->registry = $registry ?? new SettingsRegistry();
	}

	public function render(): void {
		if ( ! current_user_can( apply_filters( 'imedia_menu_capability', 'edit_theme_options' ) ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'imedia-menu' ) );
		}

		$activeTab = sanitize_key( $_GET['tab'] ?? 'general' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $activeTab === 'builder' ) {
			$this->renderPhp( $activeTab );
			return;
		}

		if ( $this->hasReactBuild() ) {
			$this->renderReactApp();
			return;
		}

		$this->renderPhp( $activeTab );
	}

	public function renderBuilderTab(): void {
		?>
		<div id="imedia-panel-builder"></div>
		<?php
	}

	private function hasReactBuild(): bool {
		return file_exists( IMEDIA_MENU_DIR . '/assets/admin/settings-page/build/index.js' );
	}

	private function renderReactApp(): void {
		?>
		<div class="wrap imedia-menu-settings">
			<div id="imedia-settings-app"></div>
		</div>
		<?php
	}

	private function renderPhp( string $activeTab ): void {
		?>
		<div class="wrap imedia-menu-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<nav class="nav-tab-wrapper">
				<?php foreach ( $this->registry->getAll() as $tab ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'tab', $tab->id() ) ); ?>"
						class="nav-tab <?php echo $activeTab === $tab->id() ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $tab->label() ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="tab-content">
				<?php if ( $activeTab === 'builder' ) : ?>
					<?php $this->renderBuilderTab(); ?>
				<?php else : ?>
					<form action="options.php" method="post">
						<?php
						settings_fields( 'imedia_menu_settings' );
						$this->renderTabContent( $activeTab );
						submit_button( __( 'Save Settings', 'imedia-menu' ) );
						?>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function renderTabContent( string $tab ): void {
		if ( $tab === 'builder' ) {
			$this->renderBuilderTab();
			return;
		}

		$tabInstance = $this->registry->get( $tab );

		if ( $tabInstance === null ) {
			echo '<p>' . esc_html__( 'Tab not found.', 'imedia-menu' ) . '</p>';
			return;
		}

		$settings = get_option( 'imedia_menu_settings', array() );
		$tabInstance->render( $settings );
	}
}
