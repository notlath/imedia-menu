import { __ } from '@wordpress/i18n';
import { SelectControl, RangeControl } from '@wordpress/components';

export default function MobileTab({ settings, onChange }) {
    return (
        <table className="form-table">
            <tbody>
                <tr>
                    <th scope="row">{__('Mobile Breakpoint', 'imedia-menu')}</th>
                    <td>
                        <RangeControl
                            value={settings.mobile_breakpoint ?? 768}
                            min={320}
                            max={1200}
                            step={16}
                            onChange={(value) =>
                                onChange({ mobile_breakpoint: value })
                            }
                        />
                        <p className="description">
                            {__(
                                'Viewport width at which the menu switches to mobile mode.',
                                'imedia-menu'
                            )}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Off-Canvas Direction', 'imedia-menu')}</th>
                    <td>
                        <SelectControl
                            value={settings.off_canvas_direction ?? 'right'}
                            options={[
                                {
                                    label: __('Slide from Right', 'imedia-menu'),
                                    value: 'right',
                                },
                                {
                                    label: __('Slide from Left', 'imedia-menu'),
                                    value: 'left',
                                },
                            ]}
                            onChange={(value) =>
                                onChange({ off_canvas_direction: value })
                            }
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">{__('Hamburger Style', 'imedia-menu')}</th>
                    <td>
                        <SelectControl
                            value={settings.hamburger_style ?? 'classic'}
                            options={[
                                {
                                    label: __('Classic (3 lines)', 'imedia-menu'),
                                    value: 'classic',
                                },
                                {
                                    label: __('X Morph', 'imedia-menu'),
                                    value: 'x-morph',
                                },
                                {
                                    label: __('Arrow Morph', 'imedia-menu'),
                                    value: 'arrow',
                                },
                            ]}
                            onChange={(value) =>
                                onChange({ hamburger_style: value })
                            }
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    );
}
