<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons\Providers;

use IMedia\Menu\Contracts\IconProvider;

final class BootstrapIconsProvider implements IconProvider {

	public function id(): string {
		return 'bootstrap-icons';
	}

	public function name(): string {
		return __( 'Bootstrap Icons', 'imedia-menu' );
	}

	public function getIcon( string $identifier ): string {
		$iconClass = 'bi-' . $identifier;
		return sprintf(
			'<span class="imm-icon imm-icon--bootstrap-icons %s" aria-hidden="true"></span>',
			esc_attr( $iconClass )
		);
	}

	public function getAvailableIcons(): array {
		return array(
			'house-door'          => __( 'Home', 'imedia-menu' ),
			'house'               => __( 'Home Alt', 'imedia-menu' ),
			'person'              => __( 'User', 'imedia-menu' ),
			'people'              => __( 'Users', 'imedia-menu' ),
			'gear'                => __( 'Gear/Settings', 'imedia-menu' ),
			'search'              => __( 'Search', 'imedia-menu' ),
			'envelope'             => __( 'Envelope', 'imedia-menu' ),
			'telephone'            => __( 'Phone', 'imedia-menu' ),
			'telephone-forward'    => __( 'Phone Forward', 'imedia-menu' ),
			'star'                 => __( 'Star', 'imedia-menu' ),
			'star-half'            => __( 'Star Half', 'imedia-menu' ),
			'heart'                => __( 'Heart', 'imedia-menu' ),
			'heart-fill'           => __( 'Heart Filled', 'imedia-menu' ),
			'cart'                 => __( 'Cart', 'imedia-menu' ),
			'cart-plus'            => __( 'Cart Plus', 'imedia-menu' ),
			'bag'                  => __( 'Bag', 'imedia-menu' ),
			'bag-plus'             => __( 'Bag Plus', 'imedia-menu' ),
			'tag'                  => __( 'Tag', 'imedia-menu' ),
			'tags'                 => __( 'Tags', 'imedia-menu' ),
			'book'                 => __( 'Book', 'imedia-menu' ),
			'file-earmark'         => __( 'File', 'imedia-menu' ),
			'file-text'            => __( 'File Text', 'imedia-menu' ),
			'file-pdf'             => __( 'File PDF', 'imedia-menu' ),
			'file-word'            => __( 'File Word', 'imedia-menu' ),
			'file-excel'           => __( 'File Excel', 'imedia-menu' ),
			'clock'                => __( 'Clock', 'imedia-menu' ),
			'calendar'             => __( 'Calendar', 'imedia-menu' ),
			'calendar-event'       => __( 'Calendar Event', 'imedia-menu' ),
			'globe'                => __( 'Globe', 'imedia-menu' ),
			'geo-alt'              => __( 'Map Marker', 'imedia-menu' ),
			'lock'                 => __( 'Lock', 'imedia-menu' ),
			'unlock'               => __( 'Unlock', 'imedia-menu' ),
			'arrow-right'          => __( 'Arrow Right', 'imedia-menu' ),
			'arrow-left'           => __( 'Arrow Left', 'imedia-menu' ),
			'arrow-up'             => __( 'Arrow Up', 'imedia-menu' ),
			'arrow-down'           => __( 'Arrow Down', 'imedia-menu' ),
			'chevron-right'        => __( 'Chevron Right', 'imedia-menu' ),
			'chevron-left'         => __( 'Chevron Left', 'imedia-menu' ),
			'chevron-up'           => __( 'Chevron Up', 'imedia-menu' ),
			'chevron-down'         => __( 'Chevron Down', 'imedia-menu' ),
			'check'                => __( 'Check', 'imedia-menu' ),
			'check-circle'         => __( 'Check Circle', 'imedia-menu' ),
			'check-square'         => __( 'Check Square', 'imedia-menu' ),
			'x'                    => __( 'Close', 'imedia-menu' ),
			'x-circle'             => __( 'Close Circle', 'imedia-menu' ),
			'plus'                 => __( 'Plus', 'imedia-menu' ),
			'plus-circle'          => __( 'Plus Circle', 'imedia-menu' ),
			'dash'                 => __( 'Minus', 'imedia-menu' ),
			'dash-circle'          => __( 'Minus Circle', 'imedia-menu' ),
			'download'             => __( 'Download', 'imedia-menu' ),
			'upload'               => __( 'Upload', 'imedia-menu' ),
			'box-arrow-up-right'   => __( 'External Link', 'imedia-menu' ),
			'link'                 => __( 'Link', 'imedia-menu' ),
			'link-45deg'           => __( 'Link 45°', 'imedia-menu' ),
			'image'                => __( 'Image', 'imedia-menu' ),
			'camera'               => __( 'Camera', 'imedia-menu' ),
			'camera-video'         => __( 'Video', 'imedia-menu' ),
			'printer'              => __( 'Print', 'imedia-menu' ),
			'chat'                 => __( 'Chat', 'imedia-menu' ),
			'chat-dots'            => __( 'Chat Dots', 'imedia-menu' ),
			'chat-text'            => __( 'Chat Text', 'imedia-menu' ),
			'share'                => __( 'Share', 'imedia-menu' ),
			'rss'                  => __( 'RSS', 'imedia-menu' ),
			'lightning'            => __( 'Lightning', 'imedia-menu' ),
			'fire'                 => __( 'Fire', 'imedia-menu' ),
			'shield'               => __( 'Shield', 'imedia-menu' ),
			'shield-check'         => __( 'Shield Check', 'imedia-menu' ),
			'flag'                 => __( 'Flag', 'imedia-menu' ),
			'bell'                 => __( 'Bell', 'imedia-menu' ),
			'gift'                 => __( 'Gift', 'imedia-menu' ),
			'bar-chart'            => __( 'Chart Bar', 'imedia-menu' ),
			'bar-chart-line'       => __( 'Chart Line', 'imedia-menu' ),
			'pie-chart'            => __( 'Chart Pie', 'imedia-menu' ),
			'layers'               => __( 'Layers', 'imedia-menu' ),
			'palette'              => __( 'Palette', 'imedia-menu' ),
			'code'                 => __( 'Code', 'imedia-menu' ),
			'terminal'             => __( 'Terminal', 'imedia-menu' ),
			'database'             => __( 'Database', 'imedia-menu' ),
			'cloud'                => __( 'Cloud', 'imedia-menu' ),
			'magic'                => __( 'Magic', 'imedia-menu' ),
			'tools'                => __( 'Tools', 'imedia-menu' ),
			'wrench'               => __( 'Wrench', 'imedia-menu' ),
			'send'                 => __( 'Send', 'imedia-menu' ),
			'pin'                  => __( 'Pin', 'imedia-menu' ),
			'trophy'               => __( 'Trophy', 'imedia-menu' ),
			'award'                => __( 'Award', 'imedia-menu' ),
			'person-plus'          => __( 'Person Plus', 'imedia-menu' ),
			'person-check'         => __( 'Person Check', 'imedia-menu' ),
			'building'             => __( 'Building', 'imedia-menu' ),
			'shop'                 => __( 'Shop', 'imedia-menu' ),
			'headset'              => __( 'Headset', 'imedia-menu' ),
			'life-preserver'       => __( 'Life Preserver', 'imedia-menu' ),
			'question-circle'      => __( 'Question Circle', 'imedia-menu' ),
			'exclamation-circle'   => __( 'Exclamation Circle', 'imedia-menu' ),
			'exclamation-triangle' => __( 'Warning Triangle', 'imedia-menu' ),
			'info-circle'          => __( 'Info Circle', 'imedia-menu' ),
			'play'                 => __( 'Play', 'imedia-menu' ),
			'pause'                => __( 'Pause', 'imedia-menu' ),
			'stop'                 => __( 'Stop', 'imedia-menu' ),
		);
	}
}
