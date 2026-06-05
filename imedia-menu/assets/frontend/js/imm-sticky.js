(function () {
  'use strict';

  var stickyMenus = document.querySelectorAll('.imm-nav--sticky');

  if (!stickyMenus.length) return;

  var prevScroll = window.pageYOffset;

  function handleScroll() {
    var currentScroll = window.pageYOffset;

    stickyMenus.forEach(function (menu) {
      if (currentScroll > prevScroll && currentScroll > menu.offsetHeight) {
        menu.style.transform = 'translateY(-100%)';
      } else {
        menu.style.transform = 'translateY(0)';
      }
    });

    prevScroll = currentScroll;
  }

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(function () {
        handleScroll();
        ticking = false;
      });
      ticking = true;
    }
  });
})();
