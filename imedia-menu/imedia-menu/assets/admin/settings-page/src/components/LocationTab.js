import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
    SelectControl,
    ToggleControl,
    TextControl,
    RangeControl,
    Button,
    Spinner,
    Notice,
    Flex,
    FlexItem,
} from '@wordpress/components';
import {
    fetchMenuLocations,
    fetchLocationOverrides,
    saveLocationOverrides,
} from '../api/locations';
import ToggleBarDesigner from './ToggleBarDesigner';

const OVERRIDABLE_FIELDS = [
    {
        key: 'sticky',
        label: __('Sticky Menu', 'imedia-menu'),
        type: 'toggle',
        description: __('Make menu sticky (uses CSS position: sticky)', 'imedia-menu'),
    },
    {
        key: 'transparent_mode',
        label: __('Transparent Mode', 'imedia-menu'),
        type: 'toggle',
        description: __('Menu bar overlays content with transparent background', 'imedia-menu'),
    },
    {
        key: 'trigger_type',
        label: __('Trigger Type', 'imedia-menu'),
        type: 'select',
        options: [
            { value: 'hover', label: __('Hover', 'imedia-menu') },
            { value: 'click', label: __('Click', 'imedia-menu') },
            { value: 'hover_click', label: __('Hover (with click toggle)', 'imedia-menu') },
        ],
    },
    {
        key: 'hover_delay',
        label: __('Hover Delay', 'imedia-menu'),
        type: 'range',
        min: 0,
        max: 500,
        default: 200,
    },
    {
        key: 'default_animation',
        label: __('Default Animation', 'imedia-menu'),
        type: 'select',
        options: [
            { value: 'fade', label: __('Fade', 'imedia-menu') },
            { value: 'slide', label: __('Slide', 'imedia-menu') },
            { value: 'none', label: __('None', 'imedia-menu') },
        ],
    },
    {
        key: 'animation_duration',
        label: __('Animation Duration', 'imedia-menu'),
        type: 'range',
        min: 0,
        max: 1000,
        default: 200,
    },
    {
        key: 'menu_bar_bg',
        label: __('Menu Bar Background', 'imedia-menu'),
        type: 'text',
        placeholder: '#ffffff',
    },
    {
        key: 'menu_bar_height',
        label: __('Menu Bar Height', 'imedia-menu'),
        type: 'range',
        min: 30,
        max: 120,
        default: 60,
    },
    {
        key: 'menu_text_color',
        label: __('Text Color', 'imedia-menu'),
        type: 'text',
        placeholder: '#333333',
    },
    {
        key: 'menu_text_hover',
        label: __('Text Hover Color', 'imedia-menu'),
        type: 'text',
        placeholder: '#0066cc',
    },
    {
        key: 'dropdown_bg',
        label: __('Dropdown/Panel Background', 'imedia-menu'),
        type: 'text',
        placeholder: '#ffffff',
    },
    {
        key: 'dark_mode_bg',
        label: __('Dark Menu Bar Background', 'imedia-menu'),
        type: 'text',
        placeholder: '#1e1e1e',
    },
    {
        key: 'dark_mode_text',
        label: __('Dark Text Color', 'imedia-menu'),
        type: 'text',
        placeholder: '#e0e0e0',
    },
    {
        key: 'dark_mode_text_hover',
        label: __('Dark Text Hover Color', 'imedia-menu'),
        type: 'text',
        placeholder: '#66b3ff',
    },
    {
        key: 'dark_mode_dropdown_bg',
        label: __('Dark Dropdown Background', 'imedia-menu'),
        type: 'text',
        placeholder: '#2d2d2d',
    },
    {
        key: 'dark_mode_dropdown_border',
        label: __('Dark Dropdown Border', 'imedia-menu'),
        type: 'text',
        placeholder: '#444444',
    },
    {
        key: 'orientation',
        label: __('Menu Orientation', 'imedia-menu'),
        type: 'select',
        description: __('Top-level layout. Accordion forces click trigger; horizontal/vertical keep the chosen trigger type.', 'imedia-menu'),
        options: [
            { value: 'horizontal', label: __('Horizontal', 'imedia-menu') },
            { value: 'vertical', label: __('Vertical (sidebar)', 'imedia-menu') },
            { value: 'accordion', label: __('Accordion (always expanded)', 'imedia-menu') },
        ],
    },
    {
        key: 'overlay',
        label: __('Page Overlay', 'imedia-menu'),
        type: 'select',
        description: __('Dim the page behind an open submenu.', 'imedia-menu'),
        options: [
            { value: 'off', label: __('Off', 'imedia-menu') },
            { value: 'desktop', label: __('Desktop only', 'imedia-menu') },
            { value: 'mobile', label: __('Mobile only', 'imedia-menu') },
            { value: 'both', label: __('Both', 'imedia-menu') },
        ],
    },
    {
        key: 'overlay_color',
        label: __('Overlay Color', 'imedia-menu'),
        type: 'text',
        placeholder: 'rgba(0,0,0,0.3)',
    },
];

const FIELD_KEYS = OVERRIDABLE_FIELDS.map((f) => f.key);

function getGlobalValue(settings, key, fieldDef) {
    const val = settings[key];
    if (val !== undefined && val !== null) {
        return val;
    }
    return fieldDef.default ?? '';
}

export default function LocationTab({ settings, onChange }) {
    const [locations, setLocations] = useState([]);
    const [allOverrides, setAllOverrides] = useState({});
    const [selectedSlug, setSelectedSlug] = useState('');
    const [localOverrides, setLocalOverrides] = useState({});
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [saveMessage, setSaveMessage] = useState(null);
    const [loadError, setLoadError] = useState(null);
    const [isDesignerOpen, setIsDesignerOpen] = useState(false);

    useEffect(() => {
        let cancelled = false;
        const load = async () => {
            setLoading(true);
            setLoadError(null);
            try {
                const [locs, overrides] = await Promise.all([
                    fetchMenuLocations(),
                    fetchLocationOverrides(),
                ]);
                if (cancelled) return;
                setLocations(locs);
                setAllOverrides(overrides);
            } catch {
                if (cancelled) return;
                setLoadError(__('Failed to load locations.', 'imedia-menu'));
            } finally {
                if (!cancelled) setLoading(false);
            }
        };
        load();
        return () => { cancelled = true; };
    }, []);

    useEffect(() => {
        if (selectedSlug && allOverrides[selectedSlug]) {
            setLocalOverrides({ ...allOverrides[selectedSlug] });
        } else {
            setLocalOverrides({});
        }
    }, [selectedSlug, allOverrides]);

    const handleToggleOverride = (key, enabled) => {
        if (enabled) {
            const globalVal = getGlobalValue(settings, key, OVERRIDABLE_FIELDS.find((f) => f.key === key));
            setLocalOverrides((prev) => ({ ...prev, [key]: globalVal }));
        } else {
            setLocalOverrides((prev) => {
                const next = { ...prev };
                delete next[key];
                return next;
            });
        }
    };

    const handleOverrideValue = (key, value) => {
        setLocalOverrides((prev) => ({ ...prev, [key]: value }));
    };

    const handleSave = async () => {
        if (!selectedSlug) return;
        setSaving(true);
        setSaveMessage(null);
        try {
            await saveLocationOverrides(selectedSlug, localOverrides);
            setAllOverrides((prev) => ({ ...prev, [selectedSlug]: { ...localOverrides } }));
            setSaveMessage({ type: 'success', text: __('Location overrides saved.', 'imedia-menu') });
        } catch {
            setSaveMessage({ type: 'error', text: __('Failed to save overrides.', 'imedia-menu') });
        } finally {
            setSaving(false);
        }
    };

    const locationOptions = [
        { value: '', label: __('Select a location...', 'imedia-menu') },
        ...locations.map((loc) => ({
            value: loc.slug,
            label: `${loc.name}${loc.hasMenu ? '' : ` (${__('no menu assigned', 'imedia-menu')})`}`,
        })),
    ];

    if (loading) {
        return (
            <div className="imedia-settings-loading">
                <Spinner />
                <p>{__('Loading location overrides...', 'imedia-menu')}</p>
            </div>
        );
    }

    if (loadError) {
        return <Notice status="error" isDismissible={false}>{loadError}</Notice>;
    }

    return (
        <>
            <p className="description">
                {__(
                    'Override global settings for individual menu locations. Select a location below to customize its behavior.',
                    'imedia-menu'
                )}
            </p>

            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            {__('Menu Location', 'imedia-menu')}
                        </th>
                        <td>
                            <SelectControl
                                value={selectedSlug}
                                options={locationOptions}
                                onChange={setSelectedSlug}
                            />
                        </td>
                    </tr>
                </tbody>
            </table>

            {selectedSlug && (
                <>
                    <table className="form-table">
                        <tbody>
                            {OVERRIDABLE_FIELDS.map((field) => {
                                const isOverridden = localOverrides.hasOwnProperty(field.key);
                                const globalVal = getGlobalValue(settings, field.key, field);

                                return (
                                    <tr key={field.key}>
                                        <th scope="row">{field.label}</th>
                                        <td>
                                            <Flex direction="column" gap={2}>
                                                <FlexItem>
                                                    <code style={{ fontSize: '12px', opacity: 0.7 }}>
                                                        {__('Global:', 'imedia-menu')} {String(globalVal)}
                                                    </code>
                                                </FlexItem>
                                                <FlexItem>
                                                    <ToggleControl
                                                        label={__('Override for this location', 'imedia-menu')}
                                                        checked={isOverridden}
                                                        onChange={(enabled) => handleToggleOverride(field.key, enabled)}
                                                    />
                                                </FlexItem>
                                                {isOverridden && (
                                                    <FlexItem>
                                                        {field.type === 'toggle' && (
                                                            <ToggleControl
                                                                label={field.description || field.label}
                                                                checked={!!localOverrides[field.key]}
                                                                onChange={(val) => handleOverrideValue(field.key, val)}
                                                            />
                                                        )}
                                                        {field.type === 'select' && (
                                                            <SelectControl
                                                                value={localOverrides[field.key] ?? globalVal}
                                                                options={field.options}
                                                                onChange={(val) => handleOverrideValue(field.key, val)}
                                                            />
                                                        )}
                                                        {field.type === 'range' && (
                                                            <RangeControl
                                                                value={localOverrides[field.key] ?? globalVal}
                                                                min={field.min}
                                                                max={field.max}
                                                                onChange={(val) => handleOverrideValue(field.key, val)}
                                                            />
                                                        )}
                                                        {field.type === 'text' && (
                                                            <TextControl
                                                                value={localOverrides[field.key] ?? globalVal}
                                                                placeholder={field.placeholder}
                                                                onChange={(val) => handleOverrideValue(field.key, val)}
                                                            />
                                                        )}
                                                    </FlexItem>
                                                )}
                                            </Flex>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>

                    <Flex justify="flex-start" gap={4}>
                        <FlexItem>
                            <Button
                                variant="primary"
                                onClick={handleSave}
                                disabled={saving}
                                isBusy={saving}
                            >
                                {saving
                                    ? __('Saving...', 'imedia-menu')
                                    : __('Save Location Overrides', 'imedia-menu')}
                            </Button>
                        </FlexItem>
                        {Object.keys(localOverrides).length > 0 && (
                            <FlexItem>
                                <Button
                                    variant="secondary"
                                    onClick={() => setLocalOverrides({})}
                                    disabled={saving}
                                >
                                    {__('Clear overrides for this location', 'imedia-menu')}
                                </Button>
                            </FlexItem>
                        )}
                    </Flex>

                    {saveMessage && (
                        <Notice
                            status={saveMessage.type}
                            isDismissible
                            onRemove={() => setSaveMessage(null)}
                            className="imedia-notice"
                        >
                            {saveMessage.text}
                        </Notice>
                    )}

                    <div style={{ marginTop: '20px', padding: '16px', background: '#f6f7f7', border: '1px solid #e5e7eb', borderRadius: '4px' }}>
                        <h3 style={{ marginTop: 0 }}>{__('Mobile Toggle Bar', 'imedia-menu')}</h3>
                        <p className="description">
                            {__(
                                'Configure the mobile toggle bar (logo, search, menu toggle, etc.) for this location. When at least one block is configured, the default mobile toggle is replaced.',
                                'imedia-menu'
                            )}
                        </p>
                        <Button
                            variant="secondary"
                            onClick={() => setIsDesignerOpen(true)}
                        >
                            {__('Open Toggle Bar Designer', 'imedia-menu')}
                        </Button>
                    </div>
                </>
            )}

            <ToggleBarDesigner
                slug={selectedSlug}
                isOpen={isDesignerOpen}
                onClose={() => setIsDesignerOpen(false)}
            />
        </>
    );
}
