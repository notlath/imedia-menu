export const DEFAULT_BLOCK_CONFIGS = {
    heading: {
        text: 'Heading',
        level: 'h3',
    },
    text: {
        content: '',
    },
    icon: {
        icon: 'dashicons:admin-home',
        size: '24px',
        color: '',
        align: 'left',
        link: '',
    },
    image: {
        image_id: 0,
        alt: '',
        caption: '',
        link: '',
    },
    banner: {
        image_id: 0,
        title: '',
        text: '',
        link: '',
        button_text: '',
        alt: '',
    },
    menu_links: {
        source: 'children',
        show_descriptions: false,
        show_icons: true,
        max_depth: 0,
    },
    gutenberg_block: {
        block_name: '',
        block_attrs: {},
        inner_content: [],
    },
    widget: {
        widget_area: '',
    },
    html: {
        html: '',
    },
    shortcode: {
        shortcode: '',
    },
    post_listing: {
        post_type: 'post',
        count: 5,
        orderby: 'date',
        order: 'DESC',
        show_thumbnail: false,
        show_excerpt: false,
        ajax_loading: false,
        taxonomy_filter: [],
    },
    taxonomy_listing: {
        taxonomy: 'category',
        hide_empty: true,
        show_count: false,
        orderby: 'name',
        order: 'ASC',
    },
    search: {
        placeholder: 'Search...',
        style: 'full',
        icon_only: false,
    },
    divider: {
        height: '1px',
        color: '#e0e0e0',
        style: 'solid',
        margin: '8px 0',
    },
    real_widget: {
        widget_class: '',
        instance: {},
        title: '',
        before_widget: '<div id="%1$s" class="widget %2$s">',
        after_widget: '</div>',
        before_title: '<h2 class="widgettitle">',
        after_title: '</h2>',
    },
    replacements: {
        template: '',
        parse_shortcodes: false,
        allowed_html: [],
    },
    tabbed: {
        tabs: [],
        orientation: 'horizontal',
        default_tab: '',
    },
    accordion: {
        items: [],
        multi_open: false,
        allow_toggle_all: false,
    },
    login_state: {
        logged_in_blocks: [],
        logged_out_blocks: [],
        fallback: 'empty',
    },
    cart: {
        display: 'icon',
        show_count: true,
        show_total: false,
        show_thumbnails: false,
        empty_text: '',
        cart_url: '',
        hide_when_empty: false,
        icon: 'dashicons-cart',
    },
    dynamic_html: {
        source: 'url',
        url: '',
        callback: '',
        method: 'GET',
        cache_ttl: 300,
        timeout: 5,
        allowed_html: [],
    },
};

export function getDefaultConfig(type) {
    return DEFAULT_BLOCK_CONFIGS[type] || {};
}
