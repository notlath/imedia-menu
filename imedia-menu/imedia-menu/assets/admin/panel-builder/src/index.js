import { createRoot } from '@wordpress/element';
import { register } from '@wordpress/data';
import { storeConfig } from './data/store';
import App from './components/App';
import './styles/builder.scss';

register(storeConfig);

// Standalone mount on the settings page (?page=imedia-menu&tab=builder)
const settingsRoot = document.getElementById('imedia-panel-builder');
if (settingsRoot) {
    const root = createRoot(settingsRoot);
    root.render(<App isModal={false} />);
}

// Mount inside the modal overlay on nav-menus.php
const modalRoot = document.getElementById('imedia-panel-builder-mount');
if (modalRoot) {
    const root = createRoot(modalRoot);
    root.render(<App isModal={true} />);
}
