<?php

define('APP_NAME', 'Fabcam Technologies');
define('APP_ENV',  'development'); // change to 'production' when deploying

// Set BASE_PATH if app lives in a subdirectory e.g. '/FabcamNewWebsite'
// Leave empty string '' if served from doc root
define('BASE_PATH', '');

if (APP_ENV === 'production') {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    define('APP_URL', ($isHttps ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? ''));
} else {
    define('APP_URL', 'http://localhost:8000');
}

define('BASE_URL', APP_URL . BASE_PATH);

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
