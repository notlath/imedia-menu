import { createRoot } from '@wordpress/element';
import { register } from '@wordpress/data';
import { storeConfig } from './data/store';
import App from './components/App';
import './styles/main.scss';

register(storeConfig);

const rootElement = document.getElementById('imedia-settings-app');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<App />);
}
