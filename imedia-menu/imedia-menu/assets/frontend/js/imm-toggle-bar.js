(function () {
  'use strict';

  var INIT_FLAG = 'data-imm-toggle-bar-init';
  var bars = document.querySelectorAll('.imm-toggle-bar');

  if (!bars.length) {
    return;
  }

  var mobileData = window.immMobileData || {
    breakpoint: 768,
    direction: 'right',
    hamburger: 'classic'
  };

  function isMobile() {
    return window.innerWidth <= mobileData.breakpoint;
  }

  function openMobileNav() {
    var mobileNav = document.querySelector('.imm-mobile-nav');
    if (!mobileNav) return;
    if (mobileNav.classList.contains('imm-mobile-nav--open')) return;

    var event = new CustomEvent('imm:togglebar:open', {
      bubbles: true,
      cancelable: true
    });
    document.dispatchEvent(event);

    var origToggles = document.querySelectorAll('.imm-mobile-toggle');
    if (origToggles.length > 0) {
      origToggles[0].click();
      return;
    }

    mobileNav.classList.add('imm-mobile-nav--open');
    mobileNav.setAttribute('aria-hidden', 'false');
    var overlay = document.querySelector('.imm-overlay');
    if (overlay) {
      overlay.classList.add('imm-overlay--visible');
    }
  }

  function wireMenuToggle(block) {
    if (block.hasAttribute(INIT_FLAG)) return;
    block.setAttribute(INIT_FLAG, '1');

    block.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      var isExpanded = block.getAttribute('aria-expanded') === 'true';
      block.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');

      openMobileNav();

      if (window.immAnnounce) {
        var label = block.getAttribute('aria-label') || 'Menu';
        window.immAnnounce(isExpanded ? label + ' menu closed' : label + ' menu opened');
      }
    });
  }

  function wireSearchToggle(block) {
    if (block.hasAttribute(INIT_FLAG)) return;
    block.setAttribute(INIT_FLAG, '1');

    var icon = block.querySelector('.imm-search-icon');
    var form = block.querySelector('.imm-search-form');
    var input = block.querySelector('.imm-search-input');

    if (!icon || !form) return;

    icon.addEventListener('click', function () {
      block.classList.add('is-expanded');
      if (input) {
        setTimeout(function () { input.focus(); }, 50);
      }
    });

    if (input) {
      input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          block.classList.remove('is-expanded');
          icon.focus();
        }
      });
    }

    document.addEventListener('click', function (e) {
      if (!block.contains(e.target)) {
        block.classList.remove('is-expanded');
      }
    });
  }

  function init() {
    var menuToggles = document.querySelectorAll('.imm-toggle-block--menu-toggle, .imm-toggle-block--menu-toggle-animated');
    menuToggles.forEach(wireMenuToggle);

    var searches = document.querySelectorAll('.imm-toggle-block--search');
    searches.forEach(wireSearchToggle);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      if (!isMobile()) {
        var searches = document.querySelectorAll('.imm-toggle-block--search.is-expanded');
        searches.forEach(function (s) { s.classList.remove('is-expanded'); });
      }
    }, 150);
  });

})();
