import { useState, useEffect, useCallback } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import {
    Button,
    Spinner,
    Notice,
    Flex,
    FlexItem,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { STORE_NAME } from '../data/constants';
import { fetchSettings, saveSettings } from '../api/settings';
import SettingsHeader from './SettingsHeader';
import GeneralTab from './GeneralTab';
import DesignTab from './DesignTab';
import AnimationsTab from './AnimationsTab';
import MobileTab from './MobileTab';
import VisibilityTab from './VisibilityTab';
import IconsTab from './IconsTab';
import PerformanceTab from './PerformanceTab';
import AdvancedTab from './AdvancedTab';
import LocationTab from './LocationTab';

const TABS = [
    { id: 'general', label: __('General', 'imedia-menu'), Component: GeneralTab },
    { id: 'design', label: __('Design', 'imedia-menu'), Component: DesignTab },
    { id: 'animations', label: __('Animations', 'imedia-menu'), Component: AnimationsTab },
    { id: 'mobile', label: __('Mobile', 'imedia-menu'), Component: MobileTab },
    { id: 'visibility', label: __('Visibility', 'imedia-menu'), Component: VisibilityTab },
    { id: 'icons', label: __('Icons', 'imedia-menu'), Component: IconsTab },
    { id: 'performance', label: __('Performance', 'imedia-menu'), Component: PerformanceTab },
    { id: 'advanced', label: __('Advanced', 'imedia-menu'), Component: AdvancedTab },
    { id: 'locations', label: __('Location Overrides', 'imedia-menu'), Component: LocationTab },
];

export default function App() {
    const [localLoading, setLocalLoading] = useState(true);
    const [localError, setLocalError] = useState(null);

    const { settings, isLoaded, isSaving, isDirty, errors, activeTab } = useSelect(
        (select) => ({
            settings: select(STORE_NAME).getSettings(),
            isLoaded: select(STORE_NAME).getIsLoaded(),
            isSaving: select(STORE_NAME).getIsSaving(),
            isDirty: select(STORE_NAME).getIsDirty(),
            errors: select(STORE_NAME).getErrors(),
            activeTab: select(STORE_NAME).getActiveTab(),
        }),
        []
    );

    const dispatch = useDispatch(STORE_NAME);

    useEffect(() => {
        const load = async () => {
            dispatch.setLoading(true);
            const data = await fetchSettings();
            dispatch.loadSettings(data);
            dispatch.setLoading(false);
            setLocalLoading(false);
        };
        load();
    }, []);

    const handleSave = useCallback(async () => {
        dispatch.setSaving(true);
        dispatch.setErrors([]);
        try {
            const updated = await saveSettings(settings);
            dispatch.loadSettings(updated);
            dispatch.saveSuccess();
        } catch (error) {
            const message = error?.message || __('Failed to save settings.', 'imedia-menu');
            dispatch.saveError([message]);
        }
    }, [settings]);

    const currentTab = TABS.find((t) => t.id === activeTab) || TABS[0];
    const TabComponent = currentTab.Component;

    if (localLoading) {
        return (
            <div className="imedia-settings-loading">
                <Spinner />
                <p>{__('Loading settings...', 'imedia-menu')}</p>
            </div>
        );
    }

    if (localError) {
        return (
            <Notice status="error" isDismissible={false}>
                {localError}
            </Notice>
        );
    }

    return (
        <div className="imedia-settings-app">
            <SettingsHeader
                isDirty={isDirty}
                isSaving={isSaving}
                onSave={handleSave}
            />

            {errors.length > 0 && (
                <Notice status="error" isDismissible={false}>
                    {errors.map((msg, i) => (
                        <div key={i}>{msg}</div>
                    ))}
                </Notice>
            )}

            <nav className="imedia-settings-tabs nav-tab-wrapper wp-clearfix">
                {TABS.map((tab) => (
                    <button
                        key={tab.id}
                        className={
                            'nav-tab' +
                            (activeTab === tab.id ? ' nav-tab-active' : '')
                        }
                        onClick={() => dispatch.setActiveTab(tab.id)}
                        type="button"
                    >
                        {tab.label}
                    </button>
                ))}
            </nav>

            <div className="imedia-settings-content">
                <TabComponent settings={settings} onChange={(updates) => dispatch.updateSettings(updates)} />
            </div>
        </div>
    );
}
