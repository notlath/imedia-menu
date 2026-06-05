/**
 * Test for iMedia Menu Editor admin JS — export/import AJAX.
 */

describe('imedia-menu-editor.js — Admin Module', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <button id="imedia-export-btn" data-action="imedia_export">Export</button>
      <input id="imedia-import-input" type="file" accept=".json" />
      <button id="imedia-import-btn" data-action="imedia_import">Import</button>
      <div id="imedia-import-status"></div>
    `;
  });

  it('should have export button with data-action attribute', () => {
    const btn = document.getElementById('imedia-export-btn');
    expect(btn).not.toBeNull();
    expect(btn.getAttribute('data-action')).toBe('imedia_export');
  });

  it('should have import file input', () => {
    const input = document.getElementById('imedia-import-input');
    expect(input).not.toBeNull();
    expect(input.getAttribute('accept')).toBe('.json');
  });

  it('should have import button with data-action attribute', () => {
    const btn = document.getElementById('imedia-import-btn');
    expect(btn).not.toBeNull();
    expect(btn.getAttribute('data-action')).toBe('imedia_import');
  });

  it('should have import status container', () => {
    const status = document.getElementById('imedia-import-status');
    expect(status).not.toBeNull();
  });
});
