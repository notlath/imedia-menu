<?php

declare(strict_types=1);

define('IMEDIA_MENU_TEST_MODE', true);

$wpTestsDir = getenv('WP_TESTS_DIR') ?: '/tmp/wordpress-tests-lib';

if (!is_dir($wpTestsDir)) {
    echo sprintf("WordPress tests directory not found: %s\n", $wpTestsDir);
    echo "Set WP_TESTS_DIR environment variable or install WordPress PHPUnit test suite.\n";
    exit(1);
}

require_once $wpTestsDir . '/includes/functions.php';
require_once $wpTestsDir . '/includes/bootstrap.php';

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
