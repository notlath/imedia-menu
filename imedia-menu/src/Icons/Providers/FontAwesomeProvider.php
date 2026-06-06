<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons\Providers;

use IMedia\Menu\Contracts\IconProvider;

final class FontAwesomeProvider implements IconProvider {

	public function id(): string {
		return 'fa';
	}

	public function name(): string {
		return __( 'Font Awesome', 'imedia-menu' );
	}

	public function getIcon( string $identifier ): string {
		$style = str_starts_with( $identifier, 'brands' ) ? 'fab' : 'fas';

		if ( str_contains( $identifier, ' ' ) ) {
			$parts      = explode( ' ', $identifier, 2 );
			$style      = $parts[0];
			$identifier = $parts[1];
		}

		return sprintf(
			'<span class="imm-icon imm-icon--fa %s fa-%s" aria-hidden="true"></span>',
			esc_attr( $style ),
			esc_attr( $identifier )
		);
	}

	public function getAvailableIcons(): array {
		return array(
			'home'                 => __( 'Home', 'imedia-menu' ),
			'search'               => __( 'Search', 'imedia-menu' ),
			'user'                 => __( 'User', 'imedia-menu' ),
			'cog'                  => __( 'Cog/Settings', 'imedia-menu' ),
			'envelope'             => __( 'Envelope', 'imedia-menu' ),
			'phone'                => __( 'Phone', 'imedia-menu' ),
			'info-circle'          => __( 'Info Circle', 'imedia-menu' ),
			'star'                 => __( 'Star', 'imedia-menu' ),
			'heart'                => __( 'Heart', 'imedia-menu' ),
			'shopping-cart'        => __( 'Shopping Cart', 'imedia-menu' ),
			'tag'                  => __( 'Tag', 'imedia-menu' ),
			'tags'                 => __( 'Tags', 'imedia-menu' ),
			'book'                 => __( 'Book', 'imedia-menu' ),
			'file'                 => __( 'File', 'imedia-menu' ),
			'clock'                => __( 'Clock', 'imedia-menu' ),
			'calendar'             => __( 'Calendar', 'imedia-menu' ),
			'globe'                => __( 'Globe', 'imedia-menu' ),
			'map-marker'           => __( 'Map Marker', 'imedia-menu' ),
			'lock'                 => __( 'Lock', 'imedia-menu' ),
			'unlock'               => __( 'Unlock', 'imedia-menu' ),
			'arrow-right'          => __( 'Arrow Right', 'imedia-menu' ),
			'arrow-left'           => __( 'Arrow Left', 'imedia-menu' ),
			'arrow-up'             => __( 'Arrow Up', 'imedia-menu' ),
			'arrow-down'           => __( 'Arrow Down', 'imedia-menu' ),
			'check'                => __( 'Check', 'imedia-menu' ),
			'times'                => __( 'Times/Close', 'imedia-menu' ),
			'plus'                 => __( 'Plus', 'imedia-menu' ),
			'minus'                => __( 'Minus', 'imedia-menu' ),
			'download'             => __( 'Download', 'imedia-menu' ),
			'upload'               => __( 'Upload', 'imedia-menu' ),
			'external-link-alt'    => __( 'External Link', 'imedia-menu' ),
			'link'                 => __( 'Link', 'imedia-menu' ),
			'image'                => __( 'Image', 'imedia-menu' ),
			'video'                => __( 'Video', 'imedia-menu' ),
			'camera'               => __( 'Camera', 'imedia-menu' ),
			'print'                => __( 'Print', 'imedia-menu' ),
			'comments'             => __( 'Comments', 'imedia-menu' ),
			'share-alt'            => __( 'Share', 'imedia-menu' ),
			'rss'                  => __( 'RSS', 'imedia-menu' ),
			'bolt'                 => __( 'Bolt/Lightning', 'imedia-menu' ),
			'fire'                 => __( 'Fire', 'imedia-menu' ),
			'shield-alt'           => __( 'Shield', 'imedia-menu' ),
			'flag'                 => __( 'Flag', 'imedia-menu' ),
			'bell'                 => __( 'Bell', 'imedia-menu' ),
			'gift'                 => __( 'Gift', 'imedia-menu' ),
			'chart-bar'            => __( 'Chart Bar', 'imedia-menu' ),
			'chart-line'           => __( 'Chart Line', 'imedia-menu' ),
			'chart-pie'            => __( 'Chart Pie', 'imedia-menu' ),
			'layer-group'          => __( 'Layers', 'imedia-menu' ),
			'palette'              => __( 'Palette', 'imedia-menu' ),
			'code'                 => __( 'Code', 'imedia-menu' ),
			'terminal'             => __( 'Terminal', 'imedia-menu' ),
			'database'             => __( 'Database', 'imedia-menu' ),
			'cloud'                => __( 'Cloud', 'imedia-menu' ),
			'download'             => __( 'Download', 'imedia-menu' ),
			'upload'               => __( 'Upload', 'imedia-menu' ),
			'magic'                => __( 'Magic/Wand', 'imedia-menu' ),
			'tools'                => __( 'Tools', 'imedia-menu' ),
			'wrench'               => __( 'Wrench', 'imedia-menu' ),
			'cogs'                 => __( 'Cogs', 'imedia-menu' ),
			'paper-plane'          => __( 'Paper Plane', 'imedia-menu' ),
			'thumbtack'            => __( 'Thumbtack', 'imedia-menu' ),
			'trophy'               => __( 'Trophy', 'imedia-menu' ),
			'medal'                => __( 'Medal', 'imedia-menu' ),
			'certificate'          => __( 'Certificate', 'imedia-menu' ),
			'badge-check'          => __( 'Badge Check', 'imedia-menu' ),
			'users'                => __( 'Users', 'imedia-menu' ),
			'user-plus'            => __( 'User Plus', 'imedia-menu' ),
			'user-check'           => __( 'User Check', 'imedia-menu' ),
			'building'             => __( 'Building', 'imedia-menu' ),
			'city'                 => __( 'City', 'imedia-menu' ),
			'store'                => __( 'Store', 'imedia-menu' ),
			'industry'             => __( 'Industry', 'imedia-menu' ),
			'headset'              => __( 'Headset', 'imedia-menu' ),
			'life-ring'            => __( 'Life Ring', 'imedia-menu' ),
			'question-circle'      => __( 'Question Circle', 'imedia-menu' ),
			'exclamation-circle'   => __( 'Exclamation Circle', 'imedia-menu' ),
			'exclamation-triangle' => __( 'Warning Triangle', 'imedia-menu' ),
		);
	}
}
