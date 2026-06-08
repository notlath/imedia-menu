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
    this.activeTrap = null;

    this.init();
  }

  Menu.prototype.getTopLevelItems = function () {
    var menu = this.element.querySelector('.imm-menu');
    return menu ? menu.querySelectorAll(':scope > .imm-item') : [];
  };

  Menu.prototype.getSubItems = function (item) {
    var sub = item.querySelector('.imm-sub, .imm-panel');
    return sub ? sub.querySelectorAll('.imm-item') : [];
  };

  Menu.prototype.getCurrentItem = function () {
    var el = document.activeElement;
    while (el && el !== document) {
      if (el.classList && el.classList.contains('imm-item')) return el;
      el = el.parentElement;
    }
    return null;
  };

  Menu.prototype.getItemIndex = function (items, item) {
    for (var i = 0; i < items.length; i++) {
      if (items[i] === item) return i;
    }
    return -1;
  };

  Menu.prototype.focusItem = function (item) {
    var link = item.querySelector('.imm-link');
    if (link) link.focus();
  };

  Menu.prototype.isTopLevel = function (item) {
    var parent = item.parentElement;
    return parent && parent.classList.contains('imm-menu');
  };

  Menu.prototype.getParentItem = function (item) {
    return item.closest('.imm-item--has-children, .imm-item--has-mega');
  };

  Menu.prototype.getParentSubmenu = function (item) {
    return item.closest('.imm-sub, .imm-panel');
  };

  Menu.prototype.getLabel = function (item) {
    var link = item.querySelector('.imm-link');
    return link ? link.textContent.trim() : '';
  };

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
    var item = this.getCurrentItem();
    var isContained = item && this.element.contains(item);

    if (!isContained && e.key !== 'Escape') return;

    switch (e.key) {
      case 'ArrowRight':
        e.preventDefault();
        this.onArrowRight(item);
        break;
      case 'ArrowLeft':
        e.preventDefault();
        this.onArrowLeft(item);
        break;
      case 'ArrowDown':
        e.preventDefault();
        this.onArrowDown(item);
        break;
      case 'ArrowUp':
        e.preventDefault();
        this.onArrowUp(item);
        break;
      case 'Home':
        e.preventDefault();
        this.onHome(item);
        break;
      case 'End':
        e.preventDefault();
        this.onEnd(item);
        break;
      case 'Enter':
      case ' ':
        e.preventDefault();
        this.onActivate(item);
        break;
      case 'Escape':
        e.preventDefault();
        this.onEscape();
        break;
      case 'Tab':
        if (!this.activeTrap) {
          this.closeAll();
        }
        break;
    }
  };

  Menu.prototype.onArrowRight = function (item) {
    if (!item) {
      var top = this.getTopLevelItems();
      if (top.length > 0) this.focusItem(top[0]);
      return;
    }

    if (!this.isTopLevel(item)) {
      var parentItem = this.getParentItem(item);
      if (parentItem) this.close(parentItem);
    }

    var topItems = this.getTopLevelItems();
    var idx = this.getItemIndex(topItems, item);
    var next = topItems[idx + 1] || topItems[0];
    if (next) {
      this.closeAll();
      this.focusItem(next);
    }
  };

  Menu.prototype.onArrowLeft = function (item) {
    if (!item) {
      var top = this.getTopLevelItems();
      if (top.length > 0) this.focusItem(top[0]);
      return;
    }

    if (!this.isTopLevel(item)) {
      var parentItem = this.getParentItem(item);
      if (parentItem) this.close(parentItem);
    }

    var topItems = this.getTopLevelItems();
    var idx = this.getItemIndex(topItems, item);
    var prev = topItems[idx - 1] || topItems[topItems.length - 1];
    if (prev) {
      this.closeAll();
      this.focusItem(prev);
    }
  };

  Menu.prototype.onArrowDown = function (item) {
    if (!item) {
      var top = this.getTopLevelItems();
      if (top.length > 0) this.focusItem(top[0]);
      return;
    }

    var isTop = this.isTopLevel(item);

    if (isTop) {
      if (item.classList.contains('imm-item--has-children') || item.classList.contains('imm-item--has-mega')) {
        this.open(item);
        var subItems = this.getSubItems(item);
        if (subItems.length > 0) {
          this.focusItem(subItems[0]);
        }
      }
    } else {
      var parentSub = this.getParentSubmenu(item);
      if (parentSub) {
        var siblings = parentSub.querySelectorAll('.imm-item');
        var idx = this.getItemIndex(siblings, item);
        if (idx < siblings.length - 1) {
          this.focusItem(siblings[idx + 1]);
        }
      }
    }
  };

  Menu.prototype.onArrowUp = function (item) {
    if (!item) return;

    var isTop = this.isTopLevel(item);

    if (isTop) return;

    var parentSub = this.getParentSubmenu(item);
    if (parentSub) {
      var siblings = parentSub.querySelectorAll('.imm-item');
      var idx = this.getItemIndex(siblings, item);

      if (idx > 0) {
        this.focusItem(siblings[idx - 1]);
      } else {
        var parentItem = this.getParentItem(item);
        if (parentItem) {
          this.close(parentItem);
          this.focusItem(parentItem);
        }
      }
    }
  };

  Menu.prototype.onHome = function (item) {
    if (!item) return;

    var isTop = this.isTopLevel(item);

    if (isTop) {
      var top = this.getTopLevelItems();
      if (top.length > 0) {
        this.closeAll();
        this.focusItem(top[0]);
      }
    } else {
      var subItems = this.getSubItems(this.getParentItem(item));
      if (subItems.length > 0) this.focusItem(subItems[0]);
    }
  };

  Menu.prototype.onEnd = function (item) {
    if (!item) return;

    var isTop = this.isTopLevel(item);

    if (isTop) {
      var top = this.getTopLevelItems();
      var last = top[top.length - 1];
      if (last) {
        this.closeAll();
        this.focusItem(last);
      }
    } else {
      var subItems = this.getSubItems(this.getParentItem(item));
      var lastSub = subItems[subItems.length - 1];
      if (lastSub) this.focusItem(lastSub);
    }
  };

  Menu.prototype.onActivate = function (item) {
    if (!item) return;

    var link = item.querySelector('.imm-link');

    if (item.classList.contains('imm-item--has-children') || item.classList.contains('imm-item--has-mega')) {
      var sub = item.querySelector('.imm-sub, .imm-panel');
      if (sub && sub.hasAttribute('hidden')) {
        this.open(item);
        var subItems = this.getSubItems(item);
        if (subItems.length > 0) {
          this.focusItem(subItems[0]);
        } else if (link && link.href && !link.hasAttribute('aria-disabled')) {
          window.location.href = link.href;
        }
      } else {
        this.close(item);
        this.focusItem(item);
      }
    } else if (link && link.href && !link.hasAttribute('aria-disabled')) {
      window.location.href = link.href;
    }
  };

  Menu.prototype.onEscape = function () {
    var open = this.element.querySelector('.imm-sub:not([hidden]), .imm-panel:not([hidden])');
    if (open) {
      var parent = open.closest('.imm-item');
      if (parent) {
        this.close(parent);
        this.focusItem(parent);
        return;
      }
    }
    this.closeAll();
    window.immAnnounce('Menu closed');
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

    this.trapFocus(sub, item);
    window.immAnnounce(this.getLabel(item) + ' menu opened');

    dispatchCustomEvent(this.element, 'imm:panel:open', {
      menuItem: item,
      panel: sub
    });
  };

  Menu.prototype.close = function (item) {
    var sub = item.querySelector('.imm-sub, .imm-panel');

    if (!sub) return;

    this.releaseTrap();

    var link = item.querySelector('.imm-link');
    if (link) {
      link.setAttribute('aria-expanded', 'false');
    }

    sub.setAttribute('hidden', '');

    window.immAnnounce(this.getLabel(item) + ' menu closed');

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

  Menu.prototype.trapFocus = function (container, triggerItem) {
    this.releaseTrap();

    var focusable = container.querySelectorAll(
      'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"]), input, textarea, select'
    );
    var first = focusable[0];
    var last = focusable[focusable.length - 1];

    if (first) first.focus();

    function onKeydown(e) {
      if (e.key !== 'Tab') return;
      if (!container.contains(document.activeElement) || !focusable.length) {
        return;
      }
      if (e.shiftKey) {
        if (document.activeElement === first) {
          e.preventDefault();
          if (last) last.focus();
        }
      } else {
        if (document.activeElement === last) {
          e.preventDefault();
          if (first) first.focus();
        }
      }
    }

    document.addEventListener('keydown', onKeydown);
    this.activeTrap = function () {
      document.removeEventListener('keydown', onKeydown);
    };
  };

  Menu.prototype.releaseTrap = function () {
    if (this.activeTrap) {
      this.activeTrap();
      this.activeTrap = null;
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

  function getLiveRegion() {
    var region = document.querySelector('.imm-live-region');
    if (!region) {
      region = document.createElement('div');
      region.className = 'imm-sr-only imm-live-region';
      region.setAttribute('aria-live', 'polite');
      region.setAttribute('aria-atomic', 'true');
      document.body.appendChild(region);
    }
    return region;
  }

  function announce(message) {
    if (!message) return;
    var region = getLiveRegion();
    region.textContent = '';
    setTimeout(function () {
      region.textContent = message;
    }, 50);
  }

  window.immGetLiveRegion = getLiveRegion;
  window.immAnnounce = announce;

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
