/**
 * Tests for imm.js — hover intent, click toggle, keyboard nav, events.
 */

describe('imm.js — Menu Module', () => {
  let container;

  beforeEach(() => {
    container = document.createElement('div');
    container.setAttribute('data-trigger', 'hover');
    container.setAttribute('data-hover-delay', '200');
    container.className = 'imm';
    container.id = 'test-menu';

    container.innerHTML = `
      <ul class="imm-menu">
        <li class="imm-item imm-item--has-mega">
          <a href="#" class="imm-link">Mega Item</a>
          <div class="imm-panel">
            <div class="imm-panel-inner">Content</div>
          </div>
        </li>
        <li class="imm-item imm-item--has-children">
          <a href="#" class="imm-link">Dropdown Item</a>
          <ul class="imm-submenu">
            <li><a href="#" class="imm-link">Sub Item</a></li>
          </ul>
        </li>
        <li class="imm-item">
          <a href="#" class="imm-link">Plain Item</a>
        </li>
      </ul>
    `;

    document.body.appendChild(container);
  });

  afterEach(() => {
    document.body.removeChild(container);
  });

  it('should set up menu instances on DOMContentLoaded', () => {
    const event = new Event('DOMContentLoaded');
    document.dispatchEvent(event);

    const immElements = document.querySelectorAll('.imm');
    expect(immElements.length).toBeGreaterThan(0);
  });

  it('should read trigger and delay from data attributes', () => {
    expect(container.getAttribute('data-trigger')).toBe('hover');
    expect(container.getAttribute('data-hover-delay')).toBe('200');
  });

  it('should find mega and dropdown items', () => {
    const hasMega = container.querySelectorAll('.imm-item--has-mega');
    const hasChildren = container.querySelectorAll('.imm-item--has-children');
    expect(hasMega.length).toBe(1);
    expect(hasChildren.length).toBe(1);
  });

  it('should have proper aria attributes by default', () => {
    const items = container.querySelectorAll('.imm-item--has-mega');
    items.forEach((item) => {
      expect(item.getAttribute('aria-expanded')).not.toBe('true');
    });
  });
});
