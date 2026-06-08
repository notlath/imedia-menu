(function () {
  'use strict';

  var stickyMenus = document.querySelectorAll('.imm-nav--sticky[data-sticky-enabled="true"]');

  if (!stickyMenus.length) return;

  function isMobile() {
    return window.innerWidth <= 768;
  }

  function shouldEnable(menu) {
    var desktop = menu.getAttribute('data-sticky-desktop') !== 'false';
    var mobile  = menu.getAttribute('data-sticky-mobile') === 'true';
    return isMobile() ? mobile : desktop;
  }

  function applyOpacity(menu) {
    var opacity = menu.getAttribute('data-sticky-opacity');
    if (opacity && parseFloat(opacity) < 1) {
      menu.style.setProperty('--imm-sticky-opacity', opacity);
    }
  }

  function applyOffset(menu) {
    var offset = parseInt(menu.getAttribute('data-sticky-offset') || '0', 10);
    if (offset > 0) {
      menu.style.top = offset + 'px';
    }
  }

  function applyExpand(menu) {
    var desktopExpand = menu.getAttribute('data-sticky-expand') === 'true';
    var mobileExpand  = menu.getAttribute('data-sticky-expand-mobile') === 'true';
    var shouldExpand  = isMobile() ? mobileExpand : desktopExpand;
    if (shouldExpand) {
      menu.classList.add('imm-nav--sticky-expanded');
    } else {
      menu.classList.remove('imm-nav--sticky-expanded');
    }
  }

  function toggleHideUntilScrollUp(menu) {
    if (menu.getAttribute('data-sticky-hide') !== 'true') return null;
    if (menu._immStickyHideController) return menu._immStickyHideController;

    var tolerance = parseInt(menu.getAttribute('data-sticky-hide-tolerance') || '10', 10);
    var offset    = parseInt(menu.getAttribute('data-sticky-hide-offset') || '0', 10);
    var lastY     = window.pageYOffset;
    var acc       = 0;

    function onScroll() {
      var currentY = window.pageYOffset;
      var delta    = currentY - lastY;

      if (currentY <= offset) {
        menu.classList.remove('imm-nav--sticky-hidden');
        acc = 0;
      } else if (delta > 0) {
        acc += delta;
        if (acc >= tolerance) {
          menu.classList.add('imm-nav--sticky-hidden');
        }
      } else if (delta < 0) {
        acc += delta;
        if (acc <= -tolerance) {
          menu.classList.remove('imm-nav--sticky-hidden');
        }
        if (acc < 0) {
          acc = 0;
        }
      }

      lastY = currentY;
    }

    var ticking = false;
    var handler = function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          onScroll();
          ticking = false;
        });
        ticking = true;
      }
    };
    window.addEventListener('scroll', handler, { passive: true });

    menu._immStickyHideController = { destroy: function () { window.removeEventListener('scroll', handler); } };
    return menu._immStickyHideController;
  }

  function initMenu(menu) {
    if (!shouldEnable(menu)) return;
    if (menu._immStickyInitialized) return;
    menu._immStickyInitialized = true;

    applyOpacity(menu);
    applyOffset(menu);
    applyExpand(menu);
    toggleHideUntilScrollUp(menu);

    var sentinel = document.createElement('div');
    sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none;';
    menu.parentNode.insertBefore(sentinel, menu);

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.target === sentinel) {
            if (entry.isIntersecting) {
              menu.classList.remove('imm-nav--stuck');
            } else {
              menu.classList.add('imm-nav--stuck');
            }
          }
        });
      },
      { threshold: [0] }
    );

    observer.observe(sentinel);
    menu._immStickyObserver = observer;
  }

  function initAll() {
    stickyMenus.forEach(initMenu);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  var resizeTicking = false;
  window.addEventListener('resize', function () {
    if (!resizeTicking) {
      window.requestAnimationFrame(function () {
        stickyMenus.forEach(function (menu) {
          applyExpand(menu);
          if (menu._immStickyInitialized && !shouldEnable(menu)) {
            menu.classList.remove('imm-nav--stuck', 'imm-nav--sticky-hidden', 'imm-nav--sticky-expanded');
          } else if (menu._immStickyInitialized && shouldEnable(menu)) {
            applyExpand(menu);
            toggleHideUntilScrollUp(menu);
          }
        });
        resizeTicking = false;
      });
      resizeTicking = true;
    }
  });
})();
