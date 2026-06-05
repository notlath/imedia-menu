<?php

declare(strict_types=1);

namespace IMedia\Menu\Admin\Settings;

final class SettingsPage
{
    public function render(): void
    {
        if (!current_user_can(apply_filters('imedia_menu_capability', 'edit_theme_options'))) {
            wp_die(esc_html__('You do not have permission to access this page.', 'imedia-menu'));
        }

        $activeTab = sanitize_key($_GET['tab'] ?? 'general'); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        ?>
        <div class="wrap imedia-menu-settings">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <nav class="nav-tab-wrapper">
                <a href="<?php echo esc_url(add_query_arg('tab', 'general')); ?>"
                   class="nav-tab <?php echo $activeTab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('General', 'imedia-menu'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('tab', 'design')); ?>"
                   class="nav-tab <?php echo $activeTab === 'design' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Design', 'imedia-menu'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('tab', 'mobile')); ?>"
                   class="nav-tab <?php echo $activeTab === 'mobile' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Mobile', 'imedia-menu'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('tab', 'performance')); ?>"
                   class="nav-tab <?php echo $activeTab === 'performance' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Performance', 'imedia-menu'); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('tab', 'advanced')); ?>"
                   class="nav-tab <?php echo $activeTab === 'advanced' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Advanced', 'imedia-menu'); ?>
                </a>
            </nav>

            <div class="tab-content">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('imedia_menu_settings');
                    $this->renderTabContent($activeTab);
                    submit_button(__('Save Settings', 'imedia-menu'));
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    private function renderTabContent(string $tab): void
    {
        $settings = get_option('imedia_menu_settings', []);

        switch ($tab) {
            case 'general':
                $this->renderGeneralTab($settings);
                break;
            case 'design':
                $this->renderDesignTab($settings);
                break;
            case 'mobile':
                $this->renderMobileTab($settings);
                break;
            case 'performance':
                $this->renderPerformanceTab($settings);
                break;
            case 'advanced':
                $this->renderAdvancedTab($settings);
                break;
        }
    }

    private function renderGeneralTab(array $settings): void
    {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable iMedia Menu', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[enabled]"
                               value="1"
                               <?php checked($settings['enabled'] ?? true); ?>
                        />
                        <?php esc_html_e('Replace WordPress menus with iMedia Menu on the frontend', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Default Trigger Type', 'imedia-menu'); ?></th>
                <td>
                    <select name="imedia_menu_settings[trigger_type]">
                        <option value="hover" <?php selected($settings['trigger_type'] ?? '', 'hover'); ?>><?php esc_html_e('Hover', 'imedia-menu'); ?></option>
                        <option value="click" <?php selected($settings['trigger_type'] ?? '', 'click'); ?>><?php esc_html_e('Click', 'imedia-menu'); ?></option>
                        <option value="hover_click" <?php selected($settings['trigger_type'] ?? '', 'hover_click'); ?>><?php esc_html_e('Hover + Click', 'imedia-menu'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Hover Intent Delay', 'imedia-menu'); ?></th>
                <td>
                    <input type="number"
                           name="imedia_menu_settings[hover_delay]"
                           value="<?php echo esc_attr($settings['hover_delay'] ?? 200); ?>"
                           min="0"
                           max="500"
                           step="50"
                           class="small-text"
                    />
                    <p class="description"><?php esc_html_e('Milliseconds before a submenu opens on hover (0-500ms).', 'imedia-menu'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Default Animation', 'imedia-menu'); ?></th>
                <td>
                    <select name="imedia_menu_settings[default_animation]">
                        <option value="fade" <?php selected($settings['default_animation'] ?? '', 'fade'); ?>><?php esc_html_e('Fade', 'imedia-menu'); ?></option>
                        <option value="slide" <?php selected($settings['default_animation'] ?? '', 'slide'); ?>><?php esc_html_e('Slide Down', 'imedia-menu'); ?></option>
                        <option value="none" <?php selected($settings['default_animation'] ?? '', 'none'); ?>><?php esc_html_e('None', 'imedia-menu'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Animation Duration', 'imedia-menu'); ?></th>
                <td>
                    <input type="number"
                           name="imedia_menu_settings[animation_duration]"
                           value="<?php echo esc_attr($settings['animation_duration'] ?? 200); ?>"
                           min="0"
                           max="1000"
                           step="50"
                           class="small-text"
                    /> ms
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Admin Bar Preview Link', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[admin_bar_preview]"
                               value="1"
                               <?php checked($settings['admin_bar_preview'] ?? true); ?>
                        />
                        <?php esc_html_e('Show iMedia Menu link in the admin bar', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    private function renderDesignTab(array $settings): void
    {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Menu Bar Background', 'imedia-menu'); ?></th>
                <td>
                    <input type="text"
                           name="imedia_menu_settings[menu_bar_bg]"
                           value="<?php echo esc_attr($settings['menu_bar_bg'] ?? ''); ?>"
                           class="imedia-color-picker"
                           placeholder="#ffffff"
                    />
                    <p class="description"><?php esc_html_e('Background color or gradient for the menu bar.', 'imedia-menu'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Menu Bar Height', 'imedia-menu'); ?></th>
                <td>
                    <input type="number"
                           name="imedia_menu_settings[menu_bar_height]"
                           value="<?php echo esc_attr($settings['menu_bar_height'] ?? 60); ?>"
                           min="30"
                           max="120"
                           class="small-text"
                    /> px
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Text Color', 'imedia-menu'); ?></th>
                <td>
                    <input type="text"
                           name="imedia_menu_settings[menu_text_color]"
                           value="<?php echo esc_attr($settings['menu_text_color'] ?? ''); ?>"
                           class="imedia-color-picker"
                           placeholder="#333333"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Text Hover Color', 'imedia-menu'); ?></th>
                <td>
                    <input type="text"
                           name="imedia_menu_settings[menu_text_hover]"
                           value="<?php echo esc_attr($settings['menu_text_hover'] ?? ''); ?>"
                           class="imedia-color-picker"
                           placeholder="#0066cc"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Dropdown/Panel Background', 'imedia-menu'); ?></th>
                <td>
                    <input type="text"
                           name="imedia_menu_settings[dropdown_bg]"
                           value="<?php echo esc_attr($settings['dropdown_bg'] ?? ''); ?>"
                           class="imedia-color-picker"
                           placeholder="#ffffff"
                    />
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Sticky Menu', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[sticky]"
                               value="1"
                               <?php checked($settings['sticky'] ?? false); ?>
                        />
                        <?php esc_html_e('Make menu sticky (uses CSS position: sticky)', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Transparent Mode', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[transparent_mode]"
                               value="1"
                               <?php checked($settings['transparent_mode'] ?? false); ?>
                        />
                        <?php esc_html_e('Menu bar overlays content with transparent background', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    private function renderMobileTab(array $settings): void
    {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Mobile Breakpoint', 'imedia-menu'); ?></th>
                <td>
                    <input type="number"
                           name="imedia_menu_settings[mobile_breakpoint]"
                           value="<?php echo esc_attr($settings['mobile_breakpoint'] ?? 768); ?>"
                           min="320"
                           max="1200"
                           step="16"
                           class="small-text"
                    /> px
                    <p class="description"><?php esc_html_e('Viewport width at which the menu switches to mobile mode.', 'imedia-menu'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Off-Canvas Direction', 'imedia-menu'); ?></th>
                <td>
                    <select name="imedia_menu_settings[off_canvas_direction]">
                        <option value="right" <?php selected($settings['off_canvas_direction'] ?? '', 'right'); ?>><?php esc_html_e('Slide from Right', 'imedia-menu'); ?></option>
                        <option value="left" <?php selected($settings['off_canvas_direction'] ?? '', 'left'); ?>><?php esc_html_e('Slide from Left', 'imedia-menu'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Hamburger Style', 'imedia-menu'); ?></th>
                <td>
                    <select name="imedia_menu_settings[hamburger_style]">
                        <option value="classic" <?php selected($settings['hamburger_style'] ?? '', 'classic'); ?>><?php esc_html_e('Classic (3 lines)', 'imedia-menu'); ?></option>
                        <option value="x-morph" <?php selected($settings['hamburger_style'] ?? '', 'x-morph'); ?>><?php esc_html_e('X Morph', 'imedia-menu'); ?></option>
                        <option value="arrow" <?php selected($settings['hamburger_style'] ?? '', 'arrow'); ?>><?php esc_html_e('Arrow Morph', 'imedia-menu'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    private function renderPerformanceTab(array $settings): void
    {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Caching', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[enable_caching]"
                               value="1"
                               <?php checked($settings['enable_caching'] ?? true); ?>
                        />
                        <?php esc_html_e('Cache rendered menus for better performance', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Cache Duration', 'imedia-menu'); ?></th>
                <td>
                    <input type="number"
                           name="imedia_menu_settings[cache_duration]"
                           value="<?php echo esc_attr($settings['cache_duration'] ?? 60); ?>"
                           min="1"
                           max="1440"
                           class="small-text"
                    /> <?php esc_html_e('minutes', 'imedia-menu'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Code Splitting', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[code_splitting]"
                               value="1"
                               <?php checked($settings['code_splitting'] ?? true); ?>
                        />
                        <?php esc_html_e('Load only the assets needed for each page', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }

    private function renderAdvancedTab(array $settings): void
    {
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Delete Data on Uninstall', 'imedia-menu'); ?></th>
                <td>
                    <label>
                        <input type="checkbox"
                               name="imedia_menu_settings[delete_data_on_uninstall]"
                               value="1"
                               <?php checked($settings['delete_data_on_uninstall'] ?? false); ?>
                        />
                        <?php esc_html_e('Remove all plugin data when deleting the plugin', 'imedia-menu'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Export Settings', 'imedia-menu'); ?></th>
                <td>
                    <button type="button"
                            class="button imedia-export-btn"
                            data-export="settings">
                        <?php esc_html_e('Download Export JSON', 'imedia-menu'); ?>
                    </button>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Import Settings', 'imedia-menu'); ?></th>
                <td>
                    <input type="file"
                           accept=".json"
                           class="imedia-import-input"
                    />
                    <button type="button"
                            class="button imedia-import-btn">
                        <?php esc_html_e('Import', 'imedia-menu'); ?>
                    </button>
                </td>
            </tr>
        </table>
        <?php
    }
}
