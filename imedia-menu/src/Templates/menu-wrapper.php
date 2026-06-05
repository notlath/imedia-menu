<nav class="<?php echo esc_attr($container_class ?? 'imm-nav'); ?>"
     role="menubar"
     aria-label="<?php echo esc_attr($menu_name ?? __('Navigation', 'imedia-menu')); ?>"
     data-trigger="<?php echo esc_attr($trigger_type ?? 'hover'); ?>"
     data-hover-delay="<?php echo esc_attr((string) ($hover_delay ?? 200)); ?>">
    <?php echo $menu_html ?? ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</nav>
