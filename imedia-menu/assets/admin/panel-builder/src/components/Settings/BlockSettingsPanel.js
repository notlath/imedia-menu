import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { TabPanel, Button } from '@wordpress/components';
import { STORE_NAME } from '../../data/constants';
import HeadingSettings from './fields/HeadingSettings';
import TextSettings from './fields/TextSettings';
import IconSettings from './fields/IconSettings';
import ImageSettings from './fields/ImageSettings';
import BannerSettings from './fields/BannerSettings';
import MenuLinksSettings from './fields/MenuLinksSettings';
import GutenbergBlockSettings from './fields/GutenbergBlockSettings';
import WidgetSettings from './fields/WidgetSettings';
import HtmlSettings from './fields/HtmlSettings';
import ShortcodeSettings from './fields/ShortcodeSettings';
import PostListingSettings from './fields/PostListingSettings';
import TaxonomyListingSettings from './fields/TaxonomyListingSettings';
import SearchSettings from './fields/SearchSettings';
import DividerSettings from './fields/DividerSettings';
import VisibilitySettings from './VisibilitySettings';
import BlockStylesPanel from './BlockStylesPanel';

const SETTINGS_MAP = {
    heading: HeadingSettings,
    text: TextSettings,
    icon: IconSettings,
    image: ImageSettings,
    banner: BannerSettings,
    menu_links: MenuLinksSettings,
    gutenberg_block: GutenbergBlockSettings,
    widget: WidgetSettings,
    html: HtmlSettings,
    shortcode: ShortcodeSettings,
    post_listing: PostListingSettings,
    taxonomy_listing: TaxonomyListingSettings,
    search: SearchSettings,
    divider: DividerSettings,
};

export default function BlockSettingsPanel() {
    const selectedBlock = useSelect(
        (select) => select(STORE_NAME).getSelectedBlock(),
        []
    );
    const dispatch = useDispatch(STORE_NAME);

    if (!selectedBlock) {
        return (
            <div className="imm-settings-empty">
                <p>{__('Select a block to edit its settings.', 'imedia-menu')}</p>
                <Button
                    variant="secondary"
                    onClick={() => dispatch.togglePanelSettings()}
                    size="small"
                >
                    {__('Panel Settings', 'imedia-menu')}
                </Button>
            </div>
        );
    }

    const { block, rowId, columnId } = selectedBlock;
    const SettingsForm = SETTINGS_MAP[block.type];

    const updateBlock = (updates) => {
        dispatch.updateBlock(rowId, columnId, block.id, updates);
    };

    const tabs = [
        {
            name: 'content',
            title: __('Content', 'imedia-menu'),
            className: 'imm-settings-tab-content',
        },
        {
            name: 'visibility',
            title: __('Visibility', 'imedia-menu'),
            className: 'imm-settings-tab-visibility',
        },
        {
            name: 'styles',
            title: __('Styles', 'imedia-menu'),
            className: 'imm-settings-tab-styles',
        },
    ];

    return (
        <div className="imm-settings-panel">
            <div className="imm-settings-panel-header">
                <h3>{__('Block Settings', 'imedia-menu')}</h3>
            </div>
            <TabPanel tabs={tabs} className="imm-settings-tabs">
                {(tab) => (
                    <div className="imm-settings-tab-content">
                        {tab.name === 'content' && SettingsForm && (
                            <SettingsForm
                                config={block.config}
                                styles={block.styles}
                                onChange={updateBlock}
                            />
                        )}
                        {tab.name === 'visibility' && (
                            <VisibilitySettings
                                visibility={block.visibility}
                                onChange={(v) => updateBlock({ visibility: v })}
                            />
                        )}
                        {tab.name === 'styles' && (
                            <BlockStylesPanel
                                styles={block.styles}
                                onChange={(s) => updateBlock({ styles: s })}
                            />
                        )}
                    </div>
                )}
            </TabPanel>
        </div>
    );
}
