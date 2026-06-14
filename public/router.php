<?php
/**
 * PHP built-in development server router.
 * Serves static files directly; routes everything else through index.php.
 * Usage: php -S localhost:8000 -t public public/router.php
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Let PHP serve real files (CSS, JS, images, etc.) directly
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

// Everything else goes through the app
require_once __DIR__ . '/index.php';
