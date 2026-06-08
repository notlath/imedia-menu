import { __ } from '@wordpress/i18n';

export const BLOCK_TYPES = [
    {
        type: 'heading',
        title: __('Heading / Label', 'imedia-menu'),
        icon: 'editor-textcolor',
        description: __('A heading tag (h2-h6, span, div).', 'imedia-menu'),
        category: 'content',
    },
    {
        type: 'text',
        title: __('Text / Rich Text', 'imedia-menu'),
        icon: 'text',
        description: __('A block of text with optional HTML.', 'imedia-menu'),
        category: 'content',
    },
    {
        type: 'icon',
        title: __('Icon', 'imedia-menu'),
        icon: 'star-filled',
        description: __('Display an icon from Dashicons or Font Awesome.', 'imedia-menu'),
        category: 'media',
    },
    {
        type: 'image',
        title: __('Image', 'imedia-menu'),
        icon: 'format-image',
        description: __('Display an image from the media library.', 'imedia-menu'),
        category: 'media',
    },
    {
        type: 'banner',
        title: __('Banner / CTA', 'imedia-menu'),
        icon: 'format-gallery',
        description: __('A banner with image, heading, text, and button.', 'imedia-menu'),
        category: 'media',
    },
    {
        type: 'menu_links',
        title: __('Menu Links', 'imedia-menu'),
        icon: 'list-view',
        description: __('Display child menu items or a custom menu.', 'imedia-menu'),
        category: 'navigation',
    },
    {
        type: 'gutenberg_block',
        title: __('Gutenberg Block', 'imedia-menu'),
        icon: 'layout',
        description: __('Render a registered Gutenberg block.', 'imedia-menu'),
        category: 'advanced',
    },
    {
        type: 'widget',
        title: __('Widget Area', 'imedia-menu'),
        icon: 'welcome-widgets-menus',
        description: __('Display a registered sidebar widget area.', 'imedia-menu'),
        category: 'advanced',
    },
    {
        type: 'html',
        title: __('Custom HTML', 'imedia-menu'),
        icon: 'editor-code',
        description: __('Arbitrary HTML markup.', 'imedia-menu'),
        category: 'advanced',
    },
    {
        type: 'shortcode',
        title: __('Shortcode', 'imedia-menu'),
        icon: 'shortcode',
        description: __('Render a WordPress shortcode.', 'imedia-menu'),
        category: 'advanced',
    },
    {
        type: 'post_listing',
        title: __('Post/Page Listing', 'imedia-menu'),
        icon: 'admin-post',
        description: __('List posts, pages, or custom post types.', 'imedia-menu'),
        category: 'dynamic',
    },
    {
        type: 'taxonomy_listing',
        title: __('Taxonomy Listing', 'imedia-menu'),
        icon: 'category',
        description: __('List taxonomy terms like categories or tags.', 'imedia-menu'),
        category: 'dynamic',
    },
    {
        type: 'search',
        title: __('Search Bar', 'imedia-menu'),
        icon: 'search',
        description: __('A search form for the mega panel.', 'imedia-menu'),
        category: 'navigation',
    },
    {
        type: 'divider',
        title: __('Divider / Spacer', 'imedia-menu'),
        icon: 'minus',
        description: __('A visual divider or spacer between blocks.', 'imedia-menu'),
        category: 'content',
    },
    {
        type: 'real_widget',
        title: __('Real Widget', 'imedia-menu'),
        icon: 'admin-generic',
        description: __('Embed a single WordPress widget instance.', 'imedia-menu'),
        category: 'advanced',
    },
    {
        type: 'replacements',
        title: __('Replacements', 'imedia-menu'),
        icon: 'tag',
        description: __('Token-substituted text. Use {user_name}, {site_title}, {date} etc.', 'imedia-menu'),
        category: 'dynamic',
    },
    {
        type: 'tabbed',
        title: __('Tabs', 'imedia-menu'),
        icon: 'index-card',
        description: __('Render child blocks as horizontal or vertical tab panels.', 'imedia-menu'),
        category: 'content',
    },
    {
        type: 'accordion',
        title: __('Accordion', 'imedia-menu'),
        icon: 'menu-alt',
        description: __('Render child blocks as collapsible accordion items.', 'imedia-menu'),
        category: 'content',
    },
    {
        type: 'login_state',
        title: __('Login State', 'imedia-menu'),
        icon: 'admin-users',
        description: __('Show different content to logged-in vs logged-out users.', 'imedia-menu'),
        category: 'dynamic',
    },
    {
        type: 'cart',
        title: __('Cart', 'imedia-menu'),
        icon: 'cart',
        description: __('WooCommerce-aware cart icon, count, and subtotal.', 'imedia-menu'),
        category: 'dynamic',
    },
    {
        type: 'dynamic_html',
        title: __('Dynamic HTML', 'imedia-menu'),
        icon: 'editor-code',
        description: __('Fetch HTML from a URL or PHP callback with caching.', 'imedia-menu'),
        category: 'advanced',
    },
];

export const BLOCK_CATEGORIES = [
    { slug: 'content', title: __('Content', 'imedia-menu') },
    { slug: 'media', title: __('Media', 'imedia-menu') },
    { slug: 'navigation', title: __('Navigation', 'imedia-menu') },
    { slug: 'dynamic', title: __('Dynamic', 'imedia-menu') },
    { slug: 'advanced', title: __('Advanced', 'imedia-menu') },
];

export function getBlockType(type) {
    return BLOCK_TYPES.find((b) => b.type === type) || null;
}

export function getBlockIcon(type) {
    const block = getBlockType(type);
    return block ? block.icon : 'marker';
}
