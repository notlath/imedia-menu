/**
 * iMedia Menu - Tabbed block frontend controller.
 *
 * Initialises click + keyboard navigation on every .imm-block--tabbed element
 * on the page. ARIA attributes are managed in PHP render(); this script only
 * toggles aria-selected and the hidden attribute.
 *
 * Used by the `tabbed` content block.
 */
(function () {
    'use strict';

    if (typeof document === 'undefined') {
        return;
    }

    function init(block) {
        if (block.dataset.immTabbedReady === '1') {
            return;
        }
        block.dataset.immTabbedReady = '1';

        var tabs = block.querySelectorAll('[role="tab"]');
        var panels = block.querySelectorAll('[role="tabpanel"]');

        function activate(tab, focus) {
            for (var i = 0; i < tabs.length; i++) {
                var t = tabs[i];
                var panel = document.getElementById(t.getAttribute('aria-controls'));
                var isCurrent = t === tab;
                t.setAttribute('aria-selected', isCurrent ? 'true' : 'false');
                t.setAttribute('tabindex', isCurrent ? '0' : '-1');
                if (panel) {
                    if (isCurrent) {
                        panel.removeAttribute('hidden');
                    } else {
                        panel.setAttribute('hidden', '');
                    }
                }
            }
            if (focus) {
                tab.focus();
            }
        }

        for (var i = 0; i < tabs.length; i++) {
            (function (tab) {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
                    activate(tab, false);
                });
                tab.addEventListener('keydown', function (e) {
                    var key = e.key;
                    var index = Array.prototype.indexOf.call(tabs, tab);
                    if (key === 'ArrowRight' || key === 'ArrowDown') {
                        e.preventDefault();
                        var next = tabs[(index + 1) % tabs.length];
                        activate(next, true);
                    } else if (key === 'ArrowLeft' || key === 'ArrowUp') {
                        e.preventDefault();
                        var prev = tabs[(index - 1 + tabs.length) % tabs.length];
                        activate(prev, true);
                    } else if (key === 'Home') {
                        e.preventDefault();
                        activate(tabs[0], true);
                    } else if (key === 'End') {
                        e.preventDefault();
                        activate(tabs[tabs.length - 1], true);
                    }
                });
            })(tabs[i]);
        }

        // Ensure the default tab is visually selected on load.
        var defaultId = block.dataset.defaultTab;
        if (defaultId) {
            for (var j = 0; j < tabs.length; j++) {
                if (tabs[j].id === 'tab-' + defaultId) {
                    activate(tabs[j], false);
                    break;
                }
            }
        }
    }

    function initAll() {
        var blocks = document.querySelectorAll('.imm-block--tabbed');
        for (var i = 0; i < blocks.length; i++) {
            init(blocks[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
