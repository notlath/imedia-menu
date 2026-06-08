import { __ } from '@wordpress/i18n';
import {
    TextControl,
    ToggleControl,
    RangeControl,
} from '@wordpress/components';

export default function DesignTab({ settings, onChange }) {
    return (
        <>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">{__('Menu Bar Background', 'imedia-menu')}</th>
                        <td>
                            <TextControl
                                value={settings.menu_bar_bg ?? ''}
                                placeholder="#ffffff"
                                onChange={(value) => onChange({ menu_bar_bg: value })}
                            />
                            <p className="description">
                                {__(
                                    'Background color or gradient for the menu bar.',
                                    'imedia-menu'
                                )}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Menu Bar Height', 'imedia-menu')}</th>
                        <td>
                            <RangeControl
                                value={settings.menu_bar_height ?? 60}
                                min={30}
                                max={120}
                                onChange={(value) => onChange({ menu_bar_height: value })}
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Text Color', 'imedia-menu')}</th>
                        <td>
                            <TextControl
                                value={settings.menu_text_color ?? ''}
                                placeholder="#333333"
                                onChange={(value) =>
                                    onChange({ menu_text_color: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Text Hover Color', 'imedia-menu')}</th>
                        <td>
                            <TextControl
                                value={settings.menu_text_hover ?? ''}
                                placeholder="#0066cc"
                                onChange={(value) =>
                                    onChange({ menu_text_hover: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Dropdown/Panel Background', 'imedia-menu')}</th>
                        <td>
                            <TextControl
                                value={settings.dropdown_bg ?? ''}
                                placeholder="#ffffff"
                                onChange={(value) =>
                                    onChange({ dropdown_bg: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Sticky Menu', 'imedia-menu')}</th>
                        <td>
                            <ToggleControl
                                label={__(
                                    'Make menu sticky (uses CSS position: sticky)',
                                    'imedia-menu'
                                )}
                                checked={settings.sticky ?? false}
                                onChange={(value) => onChange({ sticky: value })}
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">{__('Transparent Mode', 'imedia-menu')}</th>
                        <td>
                            <ToggleControl
                                label={__(
                                    'Menu bar overlays content with transparent background',
                                    'imedia-menu'
                                )}
                                checked={settings.transparent_mode ?? false}
                                onChange={(value) =>
                                    onChange({ transparent_mode: value })
                                }
                            />
                        </td>
                    </tr>
                </tbody>
            </table>

            <h2>{__('Dark Mode', 'imedia-menu')}</h2>
            <p className="description">
                {__(
                    "Configure colors for dark mode. These apply when the user's system prefers a dark color scheme.",
                    'imedia-menu'
                )}
            </p>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            {__('Enable Dark Mode', 'imedia-menu')}
                        </th>
                        <td>
                            <ToggleControl
                                label={__(
                                    'Respect prefers-color-scheme and apply dark mode colors',
                                    'imedia-menu'
                                )}
                                checked={settings.dark_mode_enabled ?? false}
                                onChange={(value) =>
                                    onChange({ dark_mode_enabled: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Dark Menu Bar Background', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                value={settings.dark_mode_bg ?? ''}
                                placeholder="#1e1e1e"
                                onChange={(value) =>
                                    onChange({ dark_mode_bg: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Dark Text Color', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                value={settings.dark_mode_text ?? ''}
                                placeholder="#e0e0e0"
                                onChange={(value) =>
                                    onChange({ dark_mode_text: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Dark Text Hover Color', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                value={settings.dark_mode_text_hover ?? ''}
                                placeholder="#66b3ff"
                                onChange={(value) =>
                                    onChange({ dark_mode_text_hover: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Dark Dropdown Background', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                value={settings.dark_mode_dropdown_bg ?? ''}
                                placeholder="#2d2d2d"
                                onChange={(value) =>
                                    onChange({ dark_mode_dropdown_bg: value })
                                }
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Dark Dropdown Border', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                value={settings.dark_mode_dropdown_border ?? ''}
                                placeholder="#444444"
                                onChange={(value) =>
                                    onChange({
                                        dark_mode_dropdown_border: value,
                                    })
                                }
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </>
    );
}
