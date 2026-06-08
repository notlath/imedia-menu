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

  function initAccordion(content) {
    content.querySelectorAll('.imm-sub, .imm-panel').forEach(function (el) {
      el.setAttribute('hidden', '');
    });

    content.querySelectorAll('.imm-item--has-children > .imm-link, .imm-item--has-mega > .imm-link').forEach(function (link) {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        var item = this.closest('.imm-item');
        if (!item) return;
        item.classList.toggle('imm-item--open');
        var isOpen = item.classList.contains('imm-item--open');
        this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        var label = this.textContent.trim();
        if (window.immAnnounce) {
          window.immAnnounce(isOpen ? label + ' menu opened' : label + ' menu closed');
        }
      });
    });
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
          initAccordion(content);
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

  var mobileTrapActive = false;

  function openMobileNav() {
    if (!mobileNav) return;
    mobileNav.classList.add('imm-mobile-nav--open');
    mobileNav.setAttribute('aria-hidden', 'false');

    if (overlay) {
      overlay.classList.add('imm-overlay--visible');
      overlay.removeAttribute('aria-hidden');
    }

    toggles.forEach(function (t) { t.setAttribute('aria-expanded', 'true'); });

    document.body.style.overflow = 'hidden';

    startMobileTrap();

    if (window.immAnnounce) {
      window.immAnnounce('Navigation menu opened');
    }

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

    stopMobileTrap();

    document.body.style.overflow = '';

    if (window.immAnnounce) {
      window.immAnnounce('Navigation menu closed');
    }

    dispatchMobileEvent('imm:mobile:close', { menu: document.querySelector('.imm-nav') });
  }

  function getMobileFocusable() {
    if (!mobileNav) return [];
    return mobileNav.querySelectorAll(
      'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"]), input, textarea, select'
    );
  }

  function handleMobileTab(e) {
    if (!mobileTrapActive) return;
    var focusable = getMobileFocusable();
    if (!focusable.length) {
      e.preventDefault();
      return;
    }
    var first = focusable[0];
    var last = focusable[focusable.length - 1];
    if (e.shiftKey) {
      if (document.activeElement === first) {
        e.preventDefault();
        last.focus();
      }
    } else {
      if (document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  }

  function startMobileTrap() {
    mobileTrapActive = true;
    document.addEventListener('keydown', handleMobileTab);
    var closeBtn = mobileNav.querySelector('.imm-mobile-close');
    if (closeBtn) closeBtn.focus();
  }

  function stopMobileTrap() {
    mobileTrapActive = false;
    document.removeEventListener('keydown', handleMobileTab);
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
