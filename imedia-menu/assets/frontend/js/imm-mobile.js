(function () {
  'use strict';

  var mobileData = window.immMobileData || {
    breakpoint: 768,
    direction: 'right',
    hamburger: 'classic'
  };

  var overlay = document.querySelector('.imm-overlay');
  var mobileNav = document.querySelector('.imm-mobile-nav');
  var toggles = document.querySelectorAll('.imm-mobile-toggle');

  if (!mobileNav) return;

  function isMobile() {
    return window.innerWidth <= mobileData.breakpoint;
  }

  function updateMode() {
    var menus = document.querySelectorAll('.imm-nav .imm-menu');

    if (isMobile()) {
      menus.forEach(function (menu) {
        var clone = menu.cloneNode(true);
        var content = mobileNav.querySelector('.imm-mobile-content');
        if (content) {
          content.innerHTML = '';
          content.appendChild(clone);
        }
      });

      document.querySelectorAll('.imm-nav .imm-menu').forEach(function (menu) {
        menu.style.display = 'none';
      });
    } else {
      var content = mobileNav.querySelector('.imm-mobile-content');
      if (content) {
        content.innerHTML = '';
      }
      document.querySelectorAll('.imm-nav .imm-menu').forEach(function (menu) {
        menu.style.display = '';
      });
    }
  }

  function openMobileNav() {
    if (!mobileNav) return;
    mobileNav.classList.add('imm-mobile-nav--open');
    mobileNav.setAttribute('aria-hidden', 'false');

    if (overlay) {
      overlay.classList.add('imm-overlay--visible');
      overlay.removeAttribute('aria-hidden');
    }

    toggles.forEach(function (t) { t.setAttribute('aria-expanded', 'true'); });

    var closeBtn = mobileNav.querySelector('.imm-mobile-close');
    if (closeBtn) closeBtn.focus();

    document.body.style.overflow = 'hidden';

    dispatchMobileEvent('imm:mobile:open', { menu: document.querySelector('.imm-nav') });
  }

  function closeMobileNav() {
    if (!mobileNav) return;
    mobileNav.classList.remove('imm-mobile-nav--open');
    mobileNav.setAttribute('aria-hidden', 'true');

    if (overlay) {
      overlay.classList.remove('imm-overlay--visible');
      overlay.setAttribute('aria-hidden', 'true');
    }

    toggles.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });

    document.body.style.overflow = '';

    dispatchMobileEvent('imm:mobile:close', { menu: document.querySelector('.imm-nav') });
  }

  function toggleMobileNav() {
    if (mobileNav.classList.contains('imm-mobile-nav--open')) {
      closeMobileNav();
    } else {
      openMobileNav();
    }
  }

  function dispatchMobileEvent(name, detail) {
    var nav = document.querySelector('.imm-nav');
    if (nav) {
      var event = new CustomEvent(name, {
        bubbles: true,
        cancelable: true,
        detail: detail
      });
      nav.dispatchEvent(event);
    }
  }

  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      toggleMobileNav();
    });
  });

  var closeBtn = mobileNav.querySelector('.imm-mobile-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', closeMobileNav);
  }

  if (overlay) {
    overlay.addEventListener('click', closeMobileNav);
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileNav.classList.contains('imm-mobile-nav--open')) {
      closeMobileNav();
      var firstToggle = toggles[0];
      if (firstToggle) firstToggle.focus();
    }
  });

  window.addEventListener('resize', function () {
    if (!isMobile() && mobileNav.classList.contains('imm-mobile-nav--open')) {
      closeMobileNav();
    }
    updateMode();
  });

  updateMode();

})();
