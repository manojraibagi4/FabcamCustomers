<?php

define('APP_NAME', 'Fabcam Technologies');
define('APP_URL',  'http://localhost:8000');
define('APP_ENV',  'development');

// Set BASE_PATH if app lives in a subdirectory e.g. '/FabcamNewWebsite'
// Leave empty string '' if served from doc root
define('BASE_PATH', '');
define('BASE_URL',  APP_URL . BASE_PATH);

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
