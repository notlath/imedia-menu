<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class SettingsPage {

	private SettingsRegistry $registry;
	private ?SettingsPageRenderer $renderer = null;

	public function __construct( ?SettingsRegistry $registry = null ) {
		$this->registry = $registry ?? new SettingsRegistry();
	}

	public function setRenderer( SettingsPageRenderer $renderer ): void {
		$this->renderer = $renderer;
	}

	public function render(): void {
		if ( $this->renderer !== null ) {
			$this->renderer->render( 'imedia-menu' );
			return;
		}

		$this->renderLegacy();
	}

	public function renderBuilderTab(): void {
		?>
		<div id="imedia-panel-builder"></div>
		<?php
	}

	private function renderLegacy(): void {
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
		$tabIds = PageRegistry::getTabIds( 'imedia-menu' );
		?>
		<div class="wrap imedia-menu-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php if ( count( $tabIds ) > 1 ) : ?>
				<nav class="nav-tab-wrapper">
					<?php foreach ( $tabIds as $tabId ) : ?>
						<?php $tab = $this->registry->get( $tabId ); ?>
						<?php
						if ( $tab === null ) {
							continue;
						}
						?>
						<a href="<?php echo esc_url( add_query_arg( 'tab', $tabId ) ); ?>"
							class="nav-tab <?php echo $activeTab === $tabId ? 'nav-tab-active' : ''; ?>">
							<?php echo esc_html( $tab->label() ); ?>
						</a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>

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
