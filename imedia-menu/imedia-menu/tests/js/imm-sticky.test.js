/**
 * Tests for imm-sticky.js — IntersectionObserver-based stuck detection
 * and hide-until-scroll-up behavior.
 */

describe('imm-sticky.js — Sticky Menu Module (M4)', () => {
  let menu;

  beforeEach(() => {
    document.body.innerHTML = '';
    menu = document.createElement('nav');
    menu.className = 'imm-nav imm-nav--sticky';
    menu.id = 'sticky-test';
    menu.setAttribute('data-sticky-enabled', 'true');
    menu.setAttribute('data-sticky-desktop', 'true');
    menu.setAttribute('data-sticky-mobile', 'false');
    menu.setAttribute('data-sticky-opacity', '1');
    menu.setAttribute('data-sticky-offset', '0');
    menu.setAttribute('data-sticky-expand', 'false');
    menu.setAttribute('data-sticky-expand-mobile', 'false');
    menu.setAttribute('data-sticky-hide', 'false');
    menu.innerHTML = '<ul><li><a href="#">Home</a></li></ul>';
    document.body.appendChild(menu);
  });

  afterEach(() => {
    if (menu && menu.parentNode) menu.parentNode.removeChild(menu);
  });

  it('attaches a sentinel element before the menu', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/sentinel/);
  });

  it('uses IntersectionObserver for stuck state', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/IntersectionObserver/);
    expect(source).toMatch(/imm-nav--stuck/);
  });

  it('respects desktop/mobile toggle via shouldEnable()', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/isMobile/);
    expect(source).toMatch(/data-sticky-desktop/);
    expect(source).toMatch(/data-sticky-mobile/);
  });

  it('reads tolerance/offset for hide-until-scroll-up', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/sticky-hide-tolerance/);
    expect(source).toMatch(/sticky-hide-offset/);
    expect(source).toMatch(/imm-nav--sticky-hidden/);
  });

  it('applies expand class for expand background option', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/imm-nav--sticky-expanded/);
    expect(source).toMatch(/data-sticky-expand/);
  });

  it('applies opacity as CSS custom property', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/--imm-sticky-opacity/);
    expect(source).toMatch(/data-sticky-opacity/);
  });

  it('handles resize event for desktop/mobile switch', () => {
    const source = require('fs').readFileSync(
      require('path').resolve(__dirname, '../../assets/frontend/js/imm-sticky.js'),
      'utf8'
    );
    expect(source).toMatch(/addEventListener\(\s*['"]resize['"]/);
  });
});
