<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class SettingsPageRenderer {

	private SettingsRegistry $registry;

	public function __construct( SettingsRegistry $registry ) {
		$this->registry = $registry;
	}

	public function render( string $pageSlug ): void {
		if ( ! current_user_can( apply_filters( 'imedia_menu_capability', 'edit_theme_options' ) ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'imedia-menu' ) );
		}

		$tabIds = PageRegistry::getTabIds( $pageSlug );

		if ( empty( $tabIds ) ) {
			echo '<p>' . esc_html__( 'Settings page not found.', 'imedia-menu' ) . '</p>';
			return;
		}

		$activeTab = sanitize_key( $_GET['tab'] ?? $tabIds[0] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! in_array( $activeTab, $tabIds, true ) ) {
			$activeTab = $tabIds[0];
		}

		?>
		<div class="wrap imedia-menu-settings">
			<h1><?php echo esc_html( PageRegistry::getPageTitle( $pageSlug ) ); ?></h1>

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

			<form action="options.php" method="post">
				<?php
				settings_fields( 'imedia_menu_settings' );
				$this->renderTabContent( $activeTab );
				submit_button( __( 'Save Settings', 'imedia-menu' ) );
				?>
			</form>
		</div>
		<?php
	}

	private function renderTabContent( string $tabId ): void {
		$settings = get_option( 'imedia_menu_settings', array() );
		$this->registry->renderTab( $tabId, $settings );
	}
}
