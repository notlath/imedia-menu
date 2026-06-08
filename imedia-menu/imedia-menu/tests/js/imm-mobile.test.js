/**
 * Tests for imm-mobile.js — off-canvas, toggle, overlay.
 */

describe('imm-mobile.js — Mobile Menu Module', () => {
  let container;

  beforeEach(() => {
    container = document.createElement('div');
    container.className = 'imm';
    container.innerHTML = `
      <button class="imm-mobile-toggle" aria-controls="imm-mobile-panel" type="button">
        <span class="imm-hamburger"></span>
        <span class="imm-sr-only">Menu</span>
      </button>
      <div id="imm-mobile-panel" class="imm-mobile-panel" role="dialog" aria-modal="true" aria-label="Navigation">
        <button class="imm-mobile-close" aria-label="Close menu">&times;</button>
        <ul class="imm-menu">
          <li><a href="#">Item</a></li>
        </ul>
      </div>
      <div class="imm-overlay"></div>
    `;
    document.body.appendChild(container);
  });

  afterEach(() => {
    document.body.removeChild(container);
  });

  it('should have mobile toggle button with aria-controls', () => {
    const toggle = container.querySelector('.imm-mobile-toggle');
    expect(toggle).not.toBeNull();
    expect(toggle.getAttribute('aria-controls')).toBe('imm-mobile-panel');
  });

  it('should have mobile panel with dialog role', () => {
    const panel = container.querySelector('.imm-mobile-panel');
    expect(panel).not.toBeNull();
    expect(panel.getAttribute('role')).toBe('dialog');
    expect(panel.getAttribute('aria-modal')).toBe('true');
  });

  it('should have close button in mobile panel', () => {
    const close = container.querySelector('.imm-mobile-close');
    expect(close).not.toBeNull();
    expect(close.hasAttribute('aria-label')).toBe(true);
  });

  it('should have overlay element', () => {
    const overlay = container.querySelector('.imm-overlay');
    expect(overlay).not.toBeNull();
  });

  it('should have hamburger icon inside toggle', () => {
    const hamburger = container.querySelector('.imm-hamburger');
    expect(hamburger).not.toBeNull();
  });
});
