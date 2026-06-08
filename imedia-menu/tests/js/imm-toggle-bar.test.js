/**
 * @jest-environment jsdom
 */

describe('imm-toggle-bar', () => {
    beforeEach(() => {
        document.body.innerHTML = '';
        jest.resetModules();
        global.window.immMobileData = { breakpoint: 768, direction: 'right', hamburger: 'classic' };
    });

    afterEach(() => {
        delete global.window.immMobileData;
    });

    function click(el) {
        const ev = new window.MouseEvent('click', { bubbles: true, cancelable: true });
        el.dispatchEvent(ev);
    }

    test('does nothing when no toggle bar exists', () => {
        require('../../assets/frontend/js/imm-toggle-bar.js');
        expect(document.querySelector('.imm-toggle-bar')).toBeNull();
    });

    test('wires menu toggle click to open mobile nav', () => {
        document.body.innerHTML = `
            <div class="imm-toggle-bar">
                <div class="imm-toggle-bar-left">
                    <button class="imm-toggle-block imm-toggle-block--logo">Logo</button>
                </div>
                <div class="imm-toggle-bar-right">
                    <button class="imm-toggle-block imm-toggle-block--menu-toggle"
                            aria-expanded="false"
                            data-block-id="b1">
                        <span class="imm-hamburger"><span></span><span></span><span></span></span>
                    </button>
                </div>
            </div>
            <div class="imm-mobile-nav" aria-hidden="true"></div>
            <div class="imm-overlay"></div>
        `;

        require('../../assets/frontend/js/imm-toggle-bar.js');

        const mobileNav = document.querySelector('.imm-mobile-nav');
        const toggle = document.querySelector('.imm-toggle-block--menu-toggle');
        click(toggle);

        expect(mobileNav.classList.contains('imm-mobile-nav--open')).toBe(true);
    });

    test('wires animated menu toggle click', () => {
        document.body.innerHTML = `
            <div class="imm-toggle-bar">
                <div class="imm-toggle-bar-right">
                    <button class="imm-toggle-block imm-toggle-block--menu-toggle-animated"
                            data-block-id="b1"
                            data-animation="arrow"
                            aria-expanded="false">
                        <span class="imm-toggle-anim-bars"><span></span><span></span><span></span></span>
                    </button>
                </div>
            </div>
            <div class="imm-mobile-nav"></div>
        `;

        require('../../assets/frontend/js/imm-toggle-bar.js');

        const toggle = document.querySelector('.imm-toggle-block--menu-toggle-animated');
        click(toggle);

        expect(toggle.getAttribute('aria-expanded')).toBe('true');
    });

    test('search toggle expands on icon click', () => {
        document.body.innerHTML = `
            <div class="imm-toggle-bar">
                <div class="imm-toggle-bar-right">
                    <div class="imm-toggle-block imm-toggle-block--search" data-block-id="b1">
                        <span class="imm-search-icon">X</span>
                        <form class="imm-search-form">
                            <input type="search" class="imm-search-input" />
                        </form>
                    </div>
                </div>
            </div>
        `;

        require('../../assets/frontend/js/imm-toggle-bar.js');

        const search = document.querySelector('.imm-toggle-block--search');
        const icon = document.querySelector('.imm-search-icon');
        click(icon);

        expect(search.classList.contains('is-expanded')).toBe(true);
    });

    test('search collapses when Escape is pressed', () => {
        document.body.innerHTML = `
            <div class="imm-toggle-bar">
                <div class="imm-toggle-bar-right">
                    <div class="imm-toggle-block imm-toggle-block--search" data-block-id="b1">
                        <span class="imm-search-icon">X</span>
                        <form class="imm-search-form">
                            <input type="search" class="imm-search-input" />
                        </form>
                    </div>
                </div>
            </div>
        `;

        require('../../assets/frontend/js/imm-toggle-bar.js');

        const search = document.querySelector('.imm-toggle-block--search');
        const input = document.querySelector('.imm-search-input');
        search.classList.add('is-expanded');

        const ev = new window.KeyboardEvent('keydown', { key: 'Escape', bubbles: true });
        input.dispatchEvent(ev);

        expect(search.classList.contains('is-expanded')).toBe(false);
    });

    test('sets init flag on first wiring', () => {
        document.body.innerHTML = `
            <div class="imm-toggle-bar">
                <div class="imm-toggle-bar-right">
                    <button class="imm-toggle-block imm-toggle-block--menu-toggle" data-block-id="b1" aria-expanded="false">X</button>
                </div>
            </div>
            <div class="imm-mobile-nav"></div>
        `;

        require('../../assets/frontend/js/imm-toggle-bar.js');

        const toggle = document.querySelector('.imm-toggle-block--menu-toggle');
        expect(toggle.hasAttribute('data-imm-toggle-bar-init')).toBe(true);
    });
});
