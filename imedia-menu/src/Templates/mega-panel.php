<div id="imm-panel-<?php echo esc_attr((string) $menu_item_id); ?>"
     class="imm-panel imm-panel--<?php echo esc_attr($panel_width ?? 'container'); ?>"
     role="menu"
     aria-label="<?php echo esc_attr($menu_item_title ?? ''); ?>"
     data-animation="<?php echo esc_attr($animation_type ?? 'fade'); ?>"
     <?php echo $panel_id ? 'hidden' : ''; ?>>
    <div class="imm-panel-inner">
        <?php echo $panel_html ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</div>
