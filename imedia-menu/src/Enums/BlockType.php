<?php

declare(strict_types=1);

namespace IMedia\Menu\Enums;

enum BlockType: string
{
    case MenuLinks      = 'menu_links';
    case Heading        = 'heading';
    case Text           = 'text';
    case Icon           = 'icon';
    case Image          = 'image';
    case Banner         = 'banner';
    case GutenbergBlock = 'gutenberg_block';
    case Widget         = 'widget';
    case Html           = 'html';
    case Shortcode      = 'shortcode';
    case PostListing    = 'post_listing';
    case TaxonomyListing = 'taxonomy_listing';
    case Search         = 'search';
    case Divider        = 'divider';

    public function label(): string
    {
        return match ($this) {
            self::MenuLinks       => __('Menu Links', 'imedia-menu'),
            self::Heading         => __('Heading / Label', 'imedia-menu'),
            self::Text            => __('Text / Rich Text', 'imedia-menu'),
            self::Icon            => __('Icon', 'imedia-menu'),
            self::Image           => __('Image', 'imedia-menu'),
            self::Banner          => __('Banner / CTA', 'imedia-menu'),
            self::GutenbergBlock  => __('Gutenberg Block', 'imedia-menu'),
            self::Widget          => __('Widget Area', 'imedia-menu'),
            self::Html            => __('Custom HTML', 'imedia-menu'),
            self::Shortcode       => __('Shortcode', 'imedia-menu'),
            self::PostListing     => __('Post/Page Listing', 'imedia-menu'),
            self::TaxonomyListing => __('Taxonomy Listing', 'imedia-menu'),
            self::Search          => __('Search Bar', 'imedia-menu'),
            self::Divider         => __('Divider / Spacer', 'imedia-menu'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
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
        };
    }
}
