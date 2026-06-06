import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

export default function VisibilityTab({ settings, onChange }) {
    return (
        <>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            {__('Default Visibility Behavior', 'imedia-menu')}
                        </th>
                        <td>
                            <SelectControl
                                value={
                                    settings.visibility_default_behavior ??
                                    'show_all'
                                }
                                options={[
                                    {
                                        label: __(
                                            'Show all items by default',
                                            'imedia-menu'
                                        ),
                                        value: 'show_all',
                                    },
                                    {
                                        label: __(
                                            'Hide items without visibility conditions',
                                            'imedia-menu'
                                        ),
                                        value: 'require_conditions',
                                    },
                                ]}
                                onChange={(value) =>
                                    onChange({
                                        visibility_default_behavior: value,
                                    })
                                }
                            />
                            <p className="description">
                                {__(
                                    'Controls how menu items behave when no visibility conditions are configured.',
                                    'imedia-menu'
                                )}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Locale Detection Method', 'imedia-menu')}
                        </th>
                        <td>
                            <SelectControl
                                value={
                                    settings.locale_detection_method ?? 'auto'
                                }
                                options={[
                                    {
                                        label: 'Auto-detect',
                                        value: 'auto',
                                    },
                                    { label: 'WPML', value: 'wpml' },
                                    {
                                        label: 'Polylang',
                                        value: 'polylang',
                                    },
                                    {
                                        label: 'TranslatePress',
                                        value: 'translatepress',
                                    },
                                ]}
                                onChange={(value) =>
                                    onChange({
                                        locale_detection_method: value,
                                    })
                                }
                            />
                            <p className="description">
                                {__(
                                    'Which multilingual plugin to use for locale-based visibility conditions.',
                                    'imedia-menu'
                                )}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h2>{__('Custom PHP Callbacks', 'imedia-menu')}</h2>
            <p className="description">
                {__(
                    "Use the imedia_menu_item_visible filter to add custom visibility logic in your theme's functions.php:",
                    'imedia-menu'
                )}
            </p>
            <pre
                style={{
                    background: '#f0f0f1',
                    padding: 12,
                    borderRadius: 4,
                    overflowX: 'auto',
                    maxWidth: 600,
                }}
            >{`add_filter( 'imedia_menu_item_visible', function ( \$visible, \$item, \$conditions ) {
    if ( in_array( 'special-page', \$item->classes, true ) ) {
        return is_page( 'secret' );
    }
    return \$visible;
}, 10, 3 );`}</pre>
        </>
    );
}
