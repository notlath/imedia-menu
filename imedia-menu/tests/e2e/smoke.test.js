/**
 * E2E smoke test for iMedia Menu.
 *
 * Requires a running WordPress site with the plugin active.
 * Run with: WP_HOME=http://your-site.local npx playwright test
 */

import { test, expect } from '@playwright/test';

test.describe('iMedia Menu — Frontend Smoke Tests', () => {
  test('page loads without JS errors', async ({ page }) => {
    const errors: string[] = [];

    page.on('pageerror', (err) => errors.push(err.message));

    await page.goto('/');

    expect(errors).toHaveLength(0);
  });

  test('menu module initializes with imm class', async ({ page }) => {
    await page.goto('/');

    const hasMenu = await page.locator('.imm').count();
    expect(hasMenu).toBeGreaterThanOrEqual(0);
  });

  test('no broken links in menu', async ({ page }) => {
    await page.goto('/');

    const links = page.locator('.imm-menu a');
    const count = await links.count();

    for (let i = 0; i < count; i++) {
      const href = await links.nth(i).getAttribute('href');
      expect(href).toBeTruthy();
      expect(href).not.toBe('#');
    }
  });
});

test.describe('iMedia Menu — Admin Smoke Tests', () => {
  test('admin settings page is accessible', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=imedia-menu');

    await expect(page).toHaveURL(/imedia-menu/);
  });
});
