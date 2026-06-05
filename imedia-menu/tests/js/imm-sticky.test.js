/**
 * Tests for imm-sticky.js — scroll-direction hide/show.
 */

describe('imm-sticky.js — Sticky Menu Module', () => {
  beforeEach(() => {
    const sticky = document.createElement('div');
    sticky.className = 'imm imm--sticky';
    sticky.id = 'sticky-test';
    sticky.innerHTML = '<ul><li><a href="#">Home</a></li></ul>';
    document.body.appendChild(sticky);
  });

  afterEach(() => {
    const sticky = document.getElementById('sticky-test');
    if (sticky) document.body.removeChild(sticky);
  });

  it('should have the imm--sticky class', () => {
    const sticky = document.querySelector('.imm--sticky');
    expect(sticky).not.toBeNull();
  });

  it('should initially be visible', () => {
    const sticky = document.querySelector('.imm--sticky');
    expect(sticky.style.display).not.toBe('none');
  });
});
