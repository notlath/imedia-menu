import { defineConfig } from '@playwright/test';

/**
 * Playwright E2E test configuration.
 *
 * Prerequisites:
 *   1. WordPress site running (e.g., wp-env, Local, or remote)
 *   2. WP_HOME environment variable set (defaults to http://localhost:8888)
 *   3. iMedia Menu plugin activated
 *   4. `npx playwright install chromium` to install browser
 *
 * Usage:
 *   WP_HOME=http://my-site.local npx playwright test
 */

export default defineConfig({
  testDir: './tests/e2e',
  timeout: 30000,
  retries: 1,
  use: {
    baseURL: process.env.WP_HOME || 'http://localhost:8888',
    headless: true,
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  projects: [
    {
      name: 'chromium',
      use: { browserName: 'chromium' },
    },
  ],
});
