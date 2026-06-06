import { resolveSelect } from '@wordpress/data';
import { STORE_NAME } from './constants';
import { loadPanel } from './actions';

export async function getPanel(state, menuItemId) {
    if (!menuItemId) return;
    const isLoaded = resolveSelect(STORE_NAME).getIsLoaded();
    if (isLoaded) return;
    await loadPanel(menuItemId);
}
