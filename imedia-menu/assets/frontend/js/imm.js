(function () {
  'use strict';

  var menuInstances = [];

  function Menu(element) {
    this.element = element;
    this.trigger = element.getAttribute('data-trigger') || 'hover';
    this.delay = parseInt(element.getAttribute('data-hover-delay')) || 200;
    this.items = element.querySelectorAll('.imm-item--has-children, .imm-item--has-mega');
    this.isMobile = false;
    this.timeouts = {};

    this.init();
  }

  Menu.prototype.init = function () {
    var self = this;

    if (this.trigger === 'hover' || this.trigger === 'hover_click') {
      this.items.forEach(function (item) {
        item.addEventListener('mouseenter', function () { self.onMouseEnter(item); });
        item.addEventListener('mouseleave', function () { self.onMouseLeave(item); });
      });
    }

    if (this.trigger === 'click' || this.trigger === 'hover_click') {
      this.items.forEach(function (item) {
        var link = item.querySelector('.imm-link');
        if (link) {
          link.addEventListener('click', function (e) { self.onClick(e, item); });
        }
      });
    }

    document.addEventListener('keydown', function (e) { self.onKeydown(e); });

    document.addEventListener('click', function (e) { self.onOutsideClick(e); });

    this.element.addEventListener('imm:panel:beforeOpen', function (e) {
      dispatchCustomEvent(self.element, 'imm:panel:beforeOpen', e.detail);
    });

    this.element.addEventListener('imm:panel:open', function (e) {
      dispatchCustomEvent(self.element, 'imm:panel:open', e.detail);
    });

    this.element.addEventListener('imm:panel:beforeClose', function (e) {
      dispatchCustomEvent(self.element, 'imm:panel:beforeClose', e.detail);
    });

    this.element.addEventListener('imm:panel:close', function (e) {
      dispatchCustomEvent(self.element, 'imm:panel:close', e.detail);
    });
  };

  Menu.prototype.onMouseEnter = function (item) {
    var self = this;
    var id = item.dataset ? item.dataset.menuItemId : '';

    if (this.timeouts[id]) {
      clearTimeout(this.timeouts[id]);
      delete this.timeouts[id];
    }

    this.timeouts[id + '_open'] = setTimeout(function () {
      self.open(item);
    }, this.delay);
  };

  Menu.prototype.onMouseLeave = function (item) {
    var id = item.dataset ? item.dataset.menuItemId : '';
    if (this.timeouts[id + '_open']) {
      clearTimeout(this.timeouts[id + '_open']);
      delete this.timeouts[id + '_open'];
    }
    this.close(item);
  };

  Menu.prototype.onClick = function (e, item) {
    var sub = item.querySelector('.imm-sub, .imm-panel');
    if (sub && sub.hasAttribute('hidden')) {
      e.preventDefault();
      this.open(item);
    } else if (sub && !sub.hasAttribute('hidden')) {
      e.preventDefault();
      this.close(item);
    }
  };

  Menu.prototype.onKeydown = function (e) {
    if (e.key === 'Escape') {
      var open = this.element.querySelector('.imm-sub:not([hidden]), .imm-panel:not([hidden])');
      if (open) {
        var parent = open.closest('.imm-item');
        if (parent) {
          this.close(parent);
          var link = parent.querySelector('.imm-link');
          if (link) link.focus();
        }
      }
    }
  };

  Menu.prototype.onOutsideClick = function (e) {
    if (!this.element.contains(e.target)) {
      this.closeAll();
    }
  };

  Menu.prototype.open = function (item) {
    var sub = item.querySelector('.imm-sub, .imm-panel');

    if (!sub) return;

    this.closeSiblings(item);

    var link = item.querySelector('.imm-link');
    if (link) {
      link.setAttribute('aria-expanded', 'true');
    }

    sub.removeAttribute('hidden');

    dispatchCustomEvent(this.element, 'imm:panel:open', {
      menuItem: item,
      panel: sub
    });
  };

  Menu.prototype.close = function (item) {
    var sub = item.querySelector('.imm-sub, .imm-panel');

    if (!sub) return;

    var link = item.querySelector('.imm-link');
    if (link) {
      link.setAttribute('aria-expanded', 'false');
    }

    sub.setAttribute('hidden', '');

    dispatchCustomEvent(this.element, 'imm:panel:close', {
      menuItem: item,
      panel: sub
    });
  };

  Menu.prototype.closeAll = function () {
    var self = this;
    this.items.forEach(function (item) { self.close(item); });
  };

  Menu.prototype.closeSiblings = function (item) {
    var self = this;
    var parent = item.parentElement;
    if (parent) {
      parent.querySelectorAll(':scope > .imm-item--has-children, :scope > .imm-item--has-mega').forEach(function (sibling) {
        if (sibling !== item) {
          self.close(sibling);
        }
      });
    }
  };

  function dispatchCustomEvent(target, name, detail) {
    var event = new CustomEvent(name, {
      bubbles: true,
      cancelable: true,
      detail: detail
    });
    target.dispatchEvent(event);
  }

  function init() {
    var menus = document.querySelectorAll('.imm-nav');
    menus.forEach(function (menu) {
      menuInstances.push(new Menu(menu));
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
