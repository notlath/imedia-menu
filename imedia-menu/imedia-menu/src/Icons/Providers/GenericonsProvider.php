<?php

declare(strict_types=1);

namespace IMedia\Menu\Icons\Providers;

use IMedia\Menu\Contracts\IconProvider;

final class GenericonsProvider implements IconProvider {

	public function id(): string {
		return 'genericons';
	}

	public function name(): string {
		return __( 'Genericons', 'imedia-menu' );
	}

	public function getIcon( string $identifier ): string {
		return sprintf(
			'<span class="imm-icon imm-icon--genericons genericon genericon-%s" aria-hidden="true"></span>',
			esc_attr( $identifier )
		);
	}

	public function getAvailableIcons(): array {
		return array(
			'standard'      => __( 'Standard', 'imedia-menu' ),
			'aside'         => __( 'Aside', 'imedia-menu' ),
			'image'         => __( 'Image', 'imedia-menu' ),
			'gallery'       => __( 'Gallery', 'imedia-menu' ),
			'video'         => __( 'Video', 'imedia-menu' ),
			'status'        => __( 'Status', 'imedia-menu' ),
			'quote'         => __( 'Quote', 'imedia-menu' ),
			'link'          => __( 'Link', 'imedia-menu' ),
			'chat'          => __( 'Chat', 'imedia-menu' ),
			'audio'         => __( 'Audio', 'imedia-menu' ),
			'home'          => __( 'Home', 'imedia-menu' ),
			'user'          => __( 'User', 'imedia-menu' ),
			'feed'          => __( 'Feed', 'imedia-menu' ),
			'search'        => __( 'Search', 'imedia-menu' ),
			'star'          => __( 'Star', 'imedia-menu' ),
			'heart'         => __( 'Heart', 'imedia-menu' ),
			'mail'          => __( 'Mail', 'imedia-menu' ),
			'comment'       => __( 'Comment', 'imedia-menu' ),
			'website'       => __( 'Website', 'imedia-menu' ),
			'cart'          => __( 'Cart', 'imedia-menu' ),
			'briefcase'     => __( 'Briefcase', 'imedia-menu' ),
			'document'      => __( 'Document', 'imedia-menu' ),
			'category'      => __( 'Category', 'imedia-menu' ),
			'tag'           => __( 'Tag', 'imedia-menu' ),
			'calendar'      => __( 'Calendar', 'imedia-menu' ),
			'phone'         => __( 'Phone', 'imedia-menu' ),
			'cog'           => __( 'Cog', 'imedia-menu' ),
			'cloud'         => __( 'Cloud', 'imedia-menu' ),
			'download'      => __( 'Download', 'imedia-menu' ),
			'upload'        => __( 'Upload', 'imedia-menu' ),
			'lock'          => __( 'Lock', 'imedia-menu' ),
			'flag'          => __( 'Flag', 'imedia-menu' ),
			'book'          => __( 'Book', 'imedia-menu' ),
			'info'          => __( 'Info', 'imedia-menu' ),
			'trash'         => __( 'Trash', 'imedia-menu' ),
			'edit'          => __( 'Edit', 'imedia-menu' ),
			'reply'         => __( 'Reply', 'imedia-menu' ),
			'refresh'       => __( 'Refresh', 'imedia-menu' ),
			'location'      => __( 'Location', 'imedia-menu' ),
			'time'          => __( 'Time', 'imedia-menu' ),
			'month'         => __( 'Month', 'imedia-menu' ),
			'day'           => __( 'Day', 'imedia-menu' ),
			'top'           => __( 'Top', 'imedia-menu' ),
			'pinned'        => __( 'Pinned', 'imedia-menu' ),
			'key'           => __( 'Key', 'imedia-menu' ),
			'github'        => __( 'GitHub', 'imedia-menu' ),
			'dribbble'      => __( 'Dribbble', 'imedia-menu' ),
			'twitter'       => __( 'Twitter', 'imedia-menu' ),
			'facebook'      => __( 'Facebook', 'imedia-menu' ),
			'pinterest'     => __( 'Pinterest', 'imedia-menu' ),
			'googleplus'    => __( 'Google+', 'imedia-menu' ),
			'linkedin'      => __( 'LinkedIn', 'imedia-menu' ),
			'instagram'     => __( 'Instagram', 'imedia-menu' ),
			'youtube'       => __( 'YouTube', 'imedia-menu' ),
			'vimeo'         => __( 'Vimeo', 'imedia-menu' ),
			'flickr'        => __( 'Flickr', 'imedia-menu' ),
			'tumblr'        => __( 'Tumblr', 'imedia-menu' ),
			'wordpress'     => __( 'WordPress', 'imedia-menu' ),
			'codepen'       => __( 'CodePen', 'imedia-menu' ),
			'digg'          => __( 'Digg', 'imedia-menu' ),
			'reddit'        => __( 'Reddit', 'imedia-menu' ),
			'skype'         => __( 'Skype', 'imedia-menu' ),
			'spotify'       => __( 'Spotify', 'imedia-menu' ),
			'twitch'        => __( 'Twitch', 'imedia-menu' ),
			'whatsapp'      => __( 'WhatsApp', 'imedia-menu' ),
			'xing'          => __( 'Xing', 'imedia-menu' ),
			'next'          => __( 'Next', 'imedia-menu' ),
			'previous'      => __( 'Previous', 'imedia-menu' ),
			'expand'        => __( 'Expand', 'imedia-menu' ),
			'collapse'      => __( 'Collapse', 'imedia-menu' ),
			'dropdown'      => __( 'Dropdown', 'imedia-menu' ),
			'subscribe'     => __( 'Subscribe', 'imedia-menu' ),
			'share'         => __( 'Share', 'imedia-menu' ),
			'notice'        => __( 'Notice', 'imedia-menu' ),
			'hierarchy'     => __( 'Hierarchy', 'imedia-menu' ),
			'sitemap'       => __( 'Sitemap', 'imedia-menu' ),
			'bug'           => __( 'Bug', 'imedia-menu' ),
			'help'          => __( 'Help', 'imedia-menu' ),
			'menu'          => __( 'Menu', 'imedia-menu' ),
			'show'          => __( 'Show', 'imedia-menu' ),
			'hide'          => __( 'Hide', 'imedia-menu' ),
			'unzoom'        => __( 'Unzoom', 'imedia-menu' ),
			'zoom'          => __( 'Zoom', 'imedia-menu' ),
		);
	}
}
