/**
 * tabbed.js — click + ARIA + keyboard navigation for the Tabbed content block.
 */
const fs = require('fs');
const path = require('path');

const SCRIPT_PATH = path.resolve(
    __dirname,
    '..',
    '..',
    'assets/frontend/js/tabbed.js'
);

function loadTabbedJs() {
    const src = fs.readFileSync(SCRIPT_PATH, 'utf8');
    // eslint-disable-next-line no-new-func
    return new Function('document', 'window', src + '\nreturn null;');
}

function buildTabbedBlock(tabs) {
    const root = document.createElement('div');
    root.className = 'imm-block imm-block--tabbed imm-block--tabbed--horizontal';
    root.setAttribute('data-default-tab', tabs[0]?.id || '');

    const tablist = document.createElement('ul');
    tablist.setAttribute('role', 'tablist');
    tablist.setAttribute('aria-orientation', 'horizontal');

    const panels = [];

    tabs.forEach((tab, i) => {
        const li = document.createElement('li');
        li.setAttribute('role', 'presentation');
        const btn = document.createElement('button');
        btn.setAttribute('type', 'button');
        btn.setAttribute('role', 'tab');
        btn.id = 'tab-' + tab.id;
        btn.setAttribute('aria-controls', 'panel-' + tab.id);
        btn.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
        btn.setAttribute('tabindex', i === 0 ? '0' : '-1');
        btn.textContent = tab.label;
        li.appendChild(btn);
        tablist.appendChild(li);

        const panel = document.createElement('div');
        panel.setAttribute('role', 'tabpanel');
        panel.id = 'panel-' + tab.id;
        panel.setAttribute('aria-labelledby', 'tab-' + tab.id);
        if (i !== 0) {
            panel.setAttribute('hidden', '');
        }
        panel.textContent = tab.content || '';
        panels.push(panel);
        root.appendChild(panel);
    });

    root.insertBefore(tablist, root.firstChild);
    return root;
}

describe('tabbed.js', () => {
    beforeEach(() => {
        document.body.innerHTML = '';
    });

    test('click on a tab activates it and hides the others', () => {
        const root = buildTabbedBlock([
            { id: 't1', label: 'A' },
            { id: 't2', label: 'B' },
        ]);
        document.body.appendChild(root);

        loadTabbedJs()(document, window);

        const tab2 = root.querySelector('#tab-t2');
        tab2.click();

        expect(tab2.getAttribute('aria-selected')).toBe('true');
        expect(tab2.getAttribute('tabindex')).toBe('0');
        expect(root.querySelector('#tab-t1').getAttribute('aria-selected')).toBe('false');
        expect(root.querySelector('#panel-t2').hasAttribute('hidden')).toBe(false);
        expect(root.querySelector('#panel-t1').hasAttribute('hidden')).toBe(true);
    });

    test('ArrowRight moves to the next tab and focuses it', () => {
        const root = buildTabbedBlock([
            { id: 't1', label: 'A' },
            { id: 't2', label: 'B' },
        ]);
        document.body.appendChild(root);
        loadTabbedJs()(document, window);

        const tab1 = root.querySelector('#tab-t1');
        tab1.focus();
        const ev = new window.KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true });
        tab1.dispatchEvent(ev);

        expect(root.querySelector('#tab-t2').getAttribute('aria-selected')).toBe('true');
        expect(root.querySelector('#tab-t1').getAttribute('aria-selected')).toBe('false');
    });

    test('ArrowLeft wraps around to the previous tab', () => {
        const root = buildTabbedBlock([
            { id: 't1', label: 'A' },
            { id: 't2', label: 'B' },
        ]);
        document.body.appendChild(root);
        loadTabbedJs()(document, window);

        const tab1 = root.querySelector('#tab-t1');
        const ev = new window.KeyboardEvent('keydown', { key: 'ArrowLeft', bubbles: true });
        tab1.dispatchEvent(ev);

        expect(root.querySelector('#tab-t2').getAttribute('aria-selected')).toBe('true');
    });

    test('Home jumps to the first tab, End to the last', () => {
        const root = buildTabbedBlock([
            { id: 't1', label: 'A' },
            { id: 't2', label: 'B' },
            { id: 't3', label: 'C' },
        ]);
        document.body.appendChild(root);
        loadTabbedJs()(document, window);

        const tab3 = root.querySelector('#tab-t3');
        tab3.dispatchEvent(new window.KeyboardEvent('keydown', { key: 'Home', bubbles: true }));
        expect(root.querySelector('#tab-t1').getAttribute('aria-selected')).toBe('true');

        tab3.dispatchEvent(new window.KeyboardEvent('keydown', { key: 'End', bubbles: true }));
        expect(root.querySelector('#tab-t3').getAttribute('aria-selected')).toBe('true');
    });

    test('mark block as already initialised to avoid double-binding', () => {
        const root = buildTabbedBlock([
            { id: 't1', label: 'A' },
            { id: 't2', label: 'B' },
        ]);
        document.body.appendChild(root);

        loadTabbedJs()(document, window);
        const tab2 = root.querySelector('#tab-t2');
        tab2.click();
        const firstSelected = tab2.getAttribute('aria-selected');

        // Simulate another init pass.
        loadTabbedJs()(document, window);
        expect(tab2.getAttribute('aria-selected')).toBe(firstSelected);
    });
});
