<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum BlockType: string {

	case MenuLinks       = 'menu_links';
	case Heading         = 'heading';
	case Text            = 'text';
	case Icon            = 'icon';
	case Image           = 'image';
	case Banner          = 'banner';
	case GutenbergBlock  = 'gutenberg_block';
	case Widget          = 'widget';
	case Html            = 'html';
	case Shortcode       = 'shortcode';
	case PostListing     = 'post_listing';
	case TaxonomyListing = 'taxonomy_listing';
	case Search          = 'search';
	case Divider         = 'divider';
	case RealWidget      = 'real_widget';
	case Replacements    = 'replacements';
	case Tabbed          = 'tabbed';
	case Accordion       = 'accordion';
	case LoginState      = 'login_state';
	case Cart            = 'cart';
	case DynamicHtml     = 'dynamic_html';

	public function label(): string {
		return match ( $this ) {
			self::MenuLinks       => __( 'Menu Links', 'imedia-menu' ),
			self::Heading         => __( 'Heading / Label', 'imedia-menu' ),
			self::Text            => __( 'Text / Rich Text', 'imedia-menu' ),
			self::Icon            => __( 'Icon', 'imedia-menu' ),
			self::Image           => __( 'Image', 'imedia-menu' ),
			self::Banner          => __( 'Banner / CTA', 'imedia-menu' ),
			self::GutenbergBlock  => __( 'Gutenberg Block', 'imedia-menu' ),
			self::Widget          => __( 'Widget Area', 'imedia-menu' ),
			self::Html            => __( 'Custom HTML', 'imedia-menu' ),
			self::Shortcode       => __( 'Shortcode', 'imedia-menu' ),
			self::PostListing     => __( 'Post/Page Listing', 'imedia-menu' ),
			self::TaxonomyListing => __( 'Taxonomy Listing', 'imedia-menu' ),
			self::Search          => __( 'Search Bar', 'imedia-menu' ),
			self::Divider         => __( 'Divider / Spacer', 'imedia-menu' ),
			self::RealWidget      => __( 'Real Widget', 'imedia-menu' ),
			self::Replacements    => __( 'Replacements', 'imedia-menu' ),
			self::Tabbed          => __( 'Tabs', 'imedia-menu' ),
			self::Accordion       => __( 'Accordion', 'imedia-menu' ),
			self::LoginState      => __( 'Login State', 'imedia-menu' ),
			self::Cart            => __( 'Cart', 'imedia-menu' ),
			self::DynamicHtml     => __( 'Dynamic HTML', 'imedia-menu' ),
		};
	}

	public function icon(): string {
		return match ( $this ) {
			self::MenuLinks       => 'dashicons-list-view',
			self::Heading         => 'dashicons-editor-textcolor',
			self::Text            => 'dashicons-text',
			self::Icon            => 'dashicons-star-filled',
			self::Image           => 'dashicons-format-image',
			self::Banner          => 'dashicons-format-gallery',
			self::GutenbergBlock  => 'dashicons-layout',
			self::Widget          => 'dashicons-welcome-widgets-menus',
			self::Html            => 'dashicons-editor-code',
			self::Shortcode       => 'dashicons-shortcode',
			self::PostListing     => 'dashicons-admin-post',
			self::TaxonomyListing => 'dashicons-category',
			self::Search          => 'dashicons-search',
			self::Divider         => 'dashicons-minus',
			self::RealWidget      => 'dashicons-admin-generic',
			self::Replacements    => 'dashicons-tag',
			self::Tabbed          => 'dashicons-index-card',
			self::Accordion       => 'dashicons-menu-alt',
			self::LoginState      => 'dashicons-admin-users',
			self::Cart            => 'dashicons-cart',
			self::DynamicHtml     => 'dashicons-editor-code',
		};
	}
}
