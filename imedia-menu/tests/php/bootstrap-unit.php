<?php

declare(strict_types=1);

$autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';

if (!file_exists($autoload)) {
    echo "Composer autoloader not found. Run 'composer install' first.\n";
    exit(1);
}

require_once dirname(__DIR__, 2) . '/tests/php/stubs.php';
require_once $autoload;

define('IMEDIA_MENU_TEST_MODE', true);
