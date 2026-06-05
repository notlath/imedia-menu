<div class="imm-overlay" aria-hidden="true"></div>
<div class="imm-mobile-nav imm-mobile-nav--<?php echo esc_attr($direction ?? 'right'); ?>"
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-label="<?php esc_attr_e('Navigation Menu', 'imedia-menu'); ?>">
    <button class="imm-mobile-close" aria-label="<?php esc_attr_e('Close navigation menu', 'imedia-menu'); ?>">
        <span class="dashicons dashicons-no" aria-hidden="true"></span>
    </button>
    <nav class="imm-mobile-content" aria-label="<?php esc_attr_e('Mobile Navigation', 'imedia-menu'); ?>">
        <?php echo $menu_html ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </nav>
</div>
