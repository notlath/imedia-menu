import { useState, useEffect, useCallback } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import { STORE_NAME } from '../data/constants';
import { fetchPanel } from '../api/panel';
import BuilderHeader from './BuilderHeader';
import BuilderCanvas from './BuilderCanvas';
import BlockPicker from './BlockPicker/BlockPicker';
import BlockSettingsPanel from './Settings/BlockSettingsPanel';
import PanelSettingsDrawer from './PanelSettings/PanelSettingsDrawer';
import TemplateSaveModal from './Template/TemplateSaveModal';
import TemplateBrowserModal from './Template/TemplateBrowserModal';
import ModalFrame from './ModalFrame';

export default function App({ isModal = false }) {
    const [localLoading, setLocalLoading] = useState(!isModal);
    const [isSaveTemplateOpen, setIsSaveTemplateOpen] = useState(false);
    const [isLoadTemplateOpen, setIsLoadTemplateOpen] = useState(false);

    const { isLoaded, isBlockPickerOpen } = useSelect(
        (select) => ({
            isLoaded: select(STORE_NAME).getIsLoaded(),
            isBlockPickerOpen: select(STORE_NAME).getIsBlockPickerOpen(),
        }),
        []
    );

    const dispatch = useDispatch(STORE_NAME);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const itemId = params.get('item_id');
        const menuIdParam = params.get('menu_id');

        if (!itemId) {
            if (!isModal) {
                setLocalLoading(false);
            }
            return;
        }

        const load = async () => {
            dispatch.setLoading(true);
            const { config, styles } = await fetchPanel(parseInt(itemId, 10));
            const menuId = config?.menuId ?? (menuIdParam ? parseInt(menuIdParam, 10) : 0);
            dispatch.loadPanel(
                parseInt(itemId, 10),
                menuId,
                config,
                styles
            );
            dispatch.setLoading(false);
            setLocalLoading(false);
        };

        load();
    }, []);

    useEffect(() => {
        if (!isModal) return;

        const handleOpenBuilder = async (e) => {
            const { itemId, menuId } = e.detail;
            if (!itemId) return;

            dispatch.setLoading(true);
            setLocalLoading(true);

            const { config, styles } = await fetchPanel(itemId);
            dispatch.loadPanel(itemId, menuId, config, styles);
            dispatch.setLoading(false);
            setLocalLoading(false);
        };

        document.addEventListener('imedia:open-builder', handleOpenBuilder);
        return () => document.removeEventListener('imedia:open-builder', handleOpenBuilder);
    }, [isModal]);

    useEffect(() => {
        if (!isModal) return;

        const handleCloseBuilder = () => {
            setLocalLoading(true);
            dispatch.resetPanel();
        };

        document.addEventListener('imedia:close-builder', handleCloseBuilder);
        return () => document.removeEventListener('imedia:close-builder', handleCloseBuilder);
    }, [isModal]);

    const handleClose = useCallback(() => {
        if (isModal) {
            document.dispatchEvent(new CustomEvent('imedia:close-builder'));
        } else {
            window.location.href = 'admin.php?page=nav-menus.php';
        }
    }, [isModal]);

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                handleClose();
            }
        };
        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [handleClose]);

    const content = (() => {
        if (localLoading) {
            return (
                <div className="imm-builder-overlay">
                    <div className="imm-builder-loading">
                        <span className="spinner is-active" />
                        <p>Loading panel...</p>
                    </div>
                </div>
            );
        }

        if (!isLoaded) {
            return (
                <div className="imm-builder-overlay">
                    <div className="imm-builder-loading">
                        <p>No menu item selected.</p>
                    </div>
                </div>
            );
        }

        return (
            <div className="imm-builder-overlay">
                <div className="imm-builder">
                    <BuilderHeader
                        isModal={isModal}
                        onOpenSaveTemplate={() => setIsSaveTemplateOpen(true)}
                        onOpenLoadTemplate={() => setIsLoadTemplateOpen(true)}
                    />
                    <div className="imm-builder-body">
                        <BuilderCanvas />
                        <aside className="imm-settings-sidebar">
                            <BlockSettingsPanel />
                        </aside>
                    </div>
                    <PanelSettingsDrawer />
                    {isBlockPickerOpen && <BlockPicker />}
                </div>

                {isSaveTemplateOpen && (
                    <TemplateSaveModal onClose={() => setIsSaveTemplateOpen(false)} />
                )}

                {isLoadTemplateOpen && (
                    <TemplateBrowserModal onClose={() => setIsLoadTemplateOpen(false)} />
                )}
            </div>
        );
    })();

    if (isModal) {
        return <ModalFrame>{content}</ModalFrame>;
    }

    return content;
}
