import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
    SelectControl,
    TextControl,
    Button,
    Flex,
    FlexItem,
} from '@wordpress/components';

const ALL_WEIGHTS = [100, 200, 300, 400, 500, 600, 700, 800, 900];

export default function FontsTab({ settings, onChange }) {
    const fonts = settings._fonts ?? [];
    const enabledFonts = settings.google_fonts ?? {};

    // Local state for the "add font" form
    const [newFont, setNewFont] = useState('');
    const [newWeights, setNewWeights] = useState('400,700');

    const availableFonts = fonts.filter((f) => !enabledFonts[f]);

    const addFont = () => {
        if (!newFont) return;
        const weights = newWeights
            .split(',')
            .map((w) => parseInt(w, 10))
            .filter((w) => !isNaN(w) && ALL_WEIGHTS.includes(w));

        onChange({
            google_fonts: {
                ...enabledFonts,
                [newFont]: {
                    weights: weights.length > 0 ? weights : [400],
                },
            },
        });

        setNewFont('');
        setNewWeights('400,700');
    };

    const removeFont = (fontName) => {
        const updated = { ...enabledFonts };
        delete updated[fontName];
        onChange({ google_fonts: updated });

        // Also clear font_family if it was set to this font
        if (settings.font_family === fontName) {
            onChange({ font_family: '' });
        }
    };

    const enabledFontNames = Object.keys(enabledFonts);

    return (
        <>
            <h2>{__('Google Fonts', 'imedia-menu')}</h2>
            <p className="description">
                {__(
                    'Select Google Fonts to load on your site. Each font can have specific weights configured.',
                    'imedia-menu'
                )}
            </p>

            <div style={{ marginTop: 16, marginBottom: 16 }}>
                {enabledFontNames.length === 0 && (
                    <p>
                        <em>
                            {__(
                                'No Google Fonts configured yet. Use the form below to add one.',
                                'imedia-menu'
                            )}
                        </em>
                    </p>
                )}
                {enabledFontNames.map((fontName) => {
                    const config = enabledFonts[fontName] ?? {};
                    const weights = config.weights ?? [400];
                    return (
                        <div
                            key={fontName}
                            style={{
                                marginBottom: 12,
                                padding: 10,
                                background: '#f0f0f1',
                                borderRadius: 4,
                            }}
                        >
                            <Flex>
                                <FlexItem>
                                    <strong>{fontName}</strong>
                                </FlexItem>
                                <FlexItem>
                                    <code style={{ fontSize: 12 }}>
                                        {weights.join(', ')}
                                    </code>
                                </FlexItem>
                                <FlexItem>
                                    <Button
                                        isSmall
                                        isDestructive
                                        variant="link"
                                        onClick={() => removeFont(fontName)}
                                        style={{ textDecoration: 'none' }}
                                    >
                                        {__('Remove', 'imedia-menu')}
                                    </Button>
                                </FlexItem>
                            </Flex>
                        </div>
                    );
                })}
            </div>

            <div
                style={{
                    marginTop: 20,
                    padding: 15,
                    background: '#f6f7f7',
                    border: '1px solid #c3c4c7',
                    borderRadius: 4,
                }}
            >
                <h3>{__('Add a Google Font', 'imedia-menu')}</h3>
                <SelectControl
                    label={__('Select Font:', 'imedia-menu')}
                    value={newFont}
                    options={[
                        {
                            label: __('— Choose a font —', 'imedia-menu'),
                            value: '',
                        },
                        ...availableFonts.map((f) => ({
                            label: f,
                            value: f,
                        })),
                    ]}
                    onChange={(value) => setNewFont(value)}
                />
                <TextControl
                    label={__(
                        'Weights (comma-separated, e.g. 400,700):',
                        'imedia-menu'
                    )}
                    value={newWeights}
                    onChange={(value) => setNewWeights(value)}
                    help={__(
                        'Valid weights: 100, 200, 300, 400, 500, 600, 700, 800, 900',
                        'imedia-menu'
                    )}
                />
                <Button
                    variant="secondary"
                    onClick={addFont}
                    disabled={!newFont}
                >
                    {__('Add Font', 'imedia-menu')}
                </Button>
                <p className="description">
                    {__(
                        'Selected fonts will appear in the list above after saving.',
                        'imedia-menu'
                    )}
                </p>
            </div>

            <h2 style={{ marginTop: 32 }}>
                {__('Menu Font Settings', 'imedia-menu')}
            </h2>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            {__('Menu Font Family', 'imedia-menu')}
                        </th>
                        <td>
                            <SelectControl
                                value={settings.font_family ?? ''}
                                options={[
                                    {
                                        label: __(
                                            '— Inherit (default) —',
                                            'imedia-menu'
                                        ),
                                        value: '',
                                    },
                                    ...enabledFontNames.map((f) => ({
                                        label: f,
                                        value: f,
                                    })),
                                ]}
                                onChange={(value) =>
                                    onChange({ font_family: value })
                                }
                            />
                            <p className="description">
                                {__(
                                    'Choose one of the enabled Google Fonts to use as the menu font. Select "Inherit" to use your theme default.',
                                    'imedia-menu'
                                )}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            {__('Menu Font Size', 'imedia-menu')}
                        </th>
                        <td>
                            <TextControl
                                type="number"
                                value={settings.font_size ?? 15}
                                min={10}
                                max={50}
                                onChange={(value) =>
                                    onChange({ font_size: parseInt(value, 10) || 15 })
                                }
                            />
                            <p className="description">
                                {__(
                                    'Font size for menu items (10–50px). Default: 15px.',
                                    'imedia-menu'
                                )}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </>
    );
}
