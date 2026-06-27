<?php

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    die('Environment file (.env) not found. Copy .env.example to .env and fill in your credentials.');
}

$env = parse_ini_file($envFile);

define('DB_HOST',    $env['DB_HOST']    ?? 'localhost');
define('DB_NAME',    $env['DB_NAME']    ?? '');
define('DB_USER',    $env['DB_USER']    ?? '');
define('DB_PASS',    $env['DB_PASS']    ?? '');
define('DB_CHARSET', $env['DB_CHARSET'] ?? 'utf8mb4');
