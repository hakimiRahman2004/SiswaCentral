<?php
// PHP built-in dev server router
// Route all requests to index.php
if (is_file(__DIR__ . '/public' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
