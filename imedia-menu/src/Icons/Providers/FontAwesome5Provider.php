<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons\Providers;

use IMedia\Menu\Contracts\IconProvider;

final class FontAwesome5Provider implements IconProvider {

	public function id(): string {
		return 'fa5';
	}

	public function name(): string {
		return __( 'Font Awesome 5', 'imedia-menu' );
	}

	public function getIcon( string $identifier ): string {
		if ( str_starts_with( $identifier, 'brands ' ) ) {
			return sprintf(
				'<span class="imm-icon imm-icon--fa5 fab fa-%s" aria-hidden="true"></span>',
				esc_attr( substr( $identifier, 7 ) )
			);
		}
		if ( str_starts_with( $identifier, 'regular ' ) ) {
			return sprintf(
				'<span class="imm-icon imm-icon--fa5 far fa-%s" aria-hidden="true"></span>',
				esc_attr( substr( $identifier, 8 ) )
			);
		}
		return sprintf(
			'<span class="imm-icon imm-icon--fa5 fas fa-%s" aria-hidden="true"></span>',
			esc_attr( $identifier )
		);
	}

	public function getAvailableIcons(): array {
		return array(
			'home'                     => __( 'Home', 'imedia-menu' ),
			'user'                     => __( 'User', 'imedia-menu' ),
			'users'                    => __( 'Users', 'imedia-menu' ),
			'cog'                      => __( 'Cog/Settings', 'imedia-menu' ),
			'cogs'                     => __( 'Cogs', 'imedia-menu' ),
			'search'                   => __( 'Search', 'imedia-menu' ),
			'envelope'                 => __( 'Envelope', 'imedia-menu' ),
			'phone'                    => __( 'Phone', 'imedia-menu' ),
			'phone-alt'                => __( 'Phone Alt', 'imedia-menu' ),
			'star'                     => __( 'Star', 'imedia-menu' ),
			'star-half-alt'            => __( 'Star Half', 'imedia-menu' ),
			'heart'                    => __( 'Heart', 'imedia-menu' ),
			'shopping-cart'            => __( 'Shopping Cart', 'imedia-menu' ),
			'shopping-bag'             => __( 'Shopping Bag', 'imedia-menu' ),
			'shopping-basket'          => __( 'Shopping Basket', 'imedia-menu' ),
			'tag'                      => __( 'Tag', 'imedia-menu' ),
			'tags'                     => __( 'Tags', 'imedia-menu' ),
			'book'                     => __( 'Book', 'imedia-menu' ),
			'file'                     => __( 'File', 'imedia-menu' ),
			'file-alt'                 => __( 'File Alt', 'imedia-menu' ),
			'file-pdf'                 => __( 'File PDF', 'imedia-menu' ),
			'file-word'                => __( 'File Word', 'imedia-menu' ),
			'file-excel'               => __( 'File Excel', 'imedia-menu' ),
			'clock'                    => __( 'Clock', 'imedia-menu' ),
			'calendar'                 => __( 'Calendar', 'imedia-menu' ),
			'calendar-alt'             => __( 'Calendar Alt', 'imedia-menu' ),
			'globe'                    => __( 'Globe', 'imedia-menu' ),
			'map-marker-alt'           => __( 'Map Marker', 'imedia-menu' ),
			'lock'                     => __( 'Lock', 'imedia-menu' ),
			'lock-open'                => __( 'Lock Open', 'imedia-menu' ),
			'unlock'                   => __( 'Unlock', 'imedia-menu' ),
			'unlock-alt'               => __( 'Unlock Alt', 'imedia-menu' ),
			'arrow-right'              => __( 'Arrow Right', 'imedia-menu' ),
			'arrow-left'               => __( 'Arrow Left', 'imedia-menu' ),
			'arrow-up'                 => __( 'Arrow Up', 'imedia-menu' ),
			'arrow-down'               => __( 'Arrow Down', 'imedia-menu' ),
			'chevron-right'            => __( 'Chevron Right', 'imedia-menu' ),
			'chevron-left'             => __( 'Chevron Left', 'imedia-menu' ),
			'chevron-up'               => __( 'Chevron Up', 'imedia-menu' ),
			'chevron-down'             => __( 'Chevron Down', 'imedia-menu' ),
			'check'                    => __( 'Check', 'imedia-menu' ),
			'check-circle'             => __( 'Check Circle', 'imedia-menu' ),
			'times'                    => __( 'Times/Close', 'imedia-menu' ),
			'times-circle'             => __( 'Times Circle', 'imedia-menu' ),
			'plus'                     => __( 'Plus', 'imedia-menu' ),
			'plus-circle'              => __( 'Plus Circle', 'imedia-menu' ),
			'minus'                    => __( 'Minus', 'imedia-menu' ),
			'minus-circle'             => __( 'Minus Circle', 'imedia-menu' ),
			'download'                 => __( 'Download', 'imedia-menu' ),
			'upload'                   => __( 'Upload', 'imedia-menu' ),
			'external-link-alt'        => __( 'External Link', 'imedia-menu' ),
			'link'                     => __( 'Link', 'imedia-menu' ),
			'image'                    => __( 'Image', 'imedia-menu' ),
			'video'                    => __( 'Video', 'imedia-menu' ),
			'camera'                   => __( 'Camera', 'imedia-menu' ),
			'print'                    => __( 'Print', 'imedia-menu' ),
			'comments'                 => __( 'Comments', 'imedia-menu' ),
			'comment'                  => __( 'Comment', 'imedia-menu' ),
			'share-alt'                => __( 'Share', 'imedia-menu' ),
			'rss'                      => __( 'RSS', 'imedia-menu' ),
			'bolt'                     => __( 'Bolt/Lightning', 'imedia-menu' ),
			'fire'                     => __( 'Fire', 'imedia-menu' ),
			'shield-alt'               => __( 'Shield', 'imedia-menu' ),
			'flag'                     => __( 'Flag', 'imedia-menu' ),
			'bell'                     => __( 'Bell', 'imedia-menu' ),
			'gift'                     => __( 'Gift', 'imedia-menu' ),
			'chart-bar'                => __( 'Chart Bar', 'imedia-menu' ),
			'chart-line'               => __( 'Chart Line', 'imedia-menu' ),
			'chart-pie'                => __( 'Chart Pie', 'imedia-menu' ),
			'layer-group'              => __( 'Layers', 'imedia-menu' ),
			'palette'                  => __( 'Palette', 'imedia-menu' ),
			'code'                     => __( 'Code', 'imedia-menu' ),
			'terminal'                 => __( 'Terminal', 'imedia-menu' ),
			'database'                 => __( 'Database', 'imedia-menu' ),
			'cloud'                    => __( 'Cloud', 'imedia-menu' ),
			'magic'                    => __( 'Magic/Wand', 'imedia-menu' ),
			'tools'                    => __( 'Tools', 'imedia-menu' ),
			'wrench'                   => __( 'Wrench', 'imedia-menu' ),
			'paper-plane'              => __( 'Paper Plane', 'imedia-menu' ),
			'thumbtack'                => __( 'Thumbtack', 'imedia-menu' ),
			'trophy'                   => __( 'Trophy', 'imedia-menu' ),
			'medal'                    => __( 'Medal', 'imedia-menu' ),
			'certificate'              => __( 'Certificate', 'imedia-menu' ),
			'badge-check'              => __( 'Badge Check', 'imedia-menu' ),
			'user-plus'                => __( 'User Plus', 'imedia-menu' ),
			'user-check'               => __( 'User Check', 'imedia-menu' ),
			'user-graduate'            => __( 'User Graduate', 'imedia-menu' ),
			'user-tie'                 => __( 'User Tie', 'imedia-menu' ),
			'building'                 => __( 'Building', 'imedia-menu' ),
			'store'                    => __( 'Store', 'imedia-menu' ),
			'industry'                 => __( 'Industry', 'imedia-menu' ),
			'headset'                  => __( 'Headset', 'imedia-menu' ),
			'life-ring'                => __( 'Life Ring', 'imedia-menu' ),
			'question-circle'          => __( 'Question Circle', 'imedia-menu' ),
			'exclamation-circle'       => __( 'Exclamation Circle', 'imedia-menu' ),
			'exclamation-triangle'     => __( 'Warning Triangle', 'imedia-menu' ),
			'info-circle'              => __( 'Info Circle', 'imedia-menu' ),
			'play'                     => __( 'Play', 'imedia-menu' ),
			'pause'                    => __( 'Pause', 'imedia-menu' ),
			'stop'                     => __( 'Stop', 'imedia-menu' ),
		);
	}
}
