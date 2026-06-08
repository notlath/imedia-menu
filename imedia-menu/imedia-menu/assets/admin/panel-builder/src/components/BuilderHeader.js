import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup } from '@wordpress/components';
import { STORE_NAME } from '../data/constants';
import { savePanel as savePanelApi } from '../api/panel';

export default function BuilderHeader({ isModal = false, onOpenSaveTemplate, onOpenLoadTemplate }) {
    const { isDirty, isSaving, canUndo, canRedo, responsiveMode, menuItemId, menuId, config, styles } =
        useSelect((select) => ({
            isDirty: select(STORE_NAME).getIsDirty(),
            isSaving: select(STORE_NAME).getIsSaving(),
            canUndo: select(STORE_NAME).canUndo(),
            canRedo: select(STORE_NAME).canRedo(),
            responsiveMode: select(STORE_NAME).getResponsiveMode(),
            menuItemId: select(STORE_NAME).getMenuItemId(),
            menuId: select(STORE_NAME).getMenuId(),
            config: select(STORE_NAME).getConfig(),
            styles: select(STORE_NAME).getStyles(),
        }), []);

    const dispatch = useDispatch(STORE_NAME);

    const handleSave = async () => {
        dispatch.setSaving(true);
        try {
            await savePanelApi(menuItemId, menuId, config, styles);
            dispatch.setDirty(false);
        } catch {
            dispatch.setErrors(['Failed to save panel']);
        } finally {
            dispatch.setSaving(false);
        }
    };

    const handleUndo = () => dispatch.undo();
    const handleRedo = () => dispatch.redo();

    const handleClose = () => {
        if (isDirty && !window.confirm(__('You have unsaved changes. Close anyway?', 'imedia-menu'))) {
            return;
        }
        if (isModal) {
            document.dispatchEvent(new CustomEvent('imedia:close-builder'));
        } else {
            window.location.href = 'admin.php?page=nav-menus.php';
        }
    };

    return (
        <div className="imm-builder-header">
            <div className="imm-builder-header-left">
                <h2 className="imm-builder-title">
                    {__('Mega Panel Builder', 'imedia-menu')}
                </h2>
                <Button
                    icon="undo"
                    label={__('Undo', 'imedia-menu')}
                    onClick={handleUndo}
                    disabled={!canUndo}
                    size="small"
                />
                <Button
                    icon="redo"
                    label={__('Redo', 'imedia-menu')}
                    onClick={handleRedo}
                    disabled={!canRedo}
                    size="small"
                />
            </div>

            <div className="imm-builder-header-center">
                <ButtonGroup>
                    {['desktop', 'tablet', 'mobile'].map((mode) => (
                        <Button
                            key={mode}
                            icon={
                                mode === 'desktop'
                                    ? 'desktop'
                                    : mode === 'tablet'
                                      ? 'tablet'
                                      : 'smartphone'
                            }
                            label={__(mode.charAt(0).toUpperCase() + mode.slice(1), 'imedia-menu')}
                            isPressed={responsiveMode === mode}
                            onClick={() => dispatch.setResponsiveMode(mode)}
                            size="small"
                        />
                    ))}
                </ButtonGroup>
            </div>

            <div className="imm-builder-header-right">
                <Button
                    variant="secondary"
                    size="small"
                    onClick={onOpenSaveTemplate}
                >
                    {__('Save as Template', 'imedia-menu')}
                </Button>
                <Button
                    variant="secondary"
                    size="small"
                    onClick={onOpenLoadTemplate}
                >
                    {__('Load Template', 'imedia-menu')}
                </Button>
                <span className="imm-unsaved-indicator">
                    {isDirty && <span className="imm-unsaved-dot" aria-hidden="true" />}
                    {isDirty ? __('Unsaved', 'imedia-menu') : __('Saved', 'imedia-menu')}
                </span>
                <Button
                    variant="primary"
                    onClick={handleSave}
                    disabled={!isDirty || isSaving}
                    isBusy={isSaving}
                >
                    {isSaving ? __('Saving...', 'imedia-menu') : __('Save Panel', 'imedia-menu')}
                </Button>
                <Button variant="secondary" onClick={handleClose}>
                    {__('Close', 'imedia-menu')}
                </Button>
            </div>
        </div>
    );
}
