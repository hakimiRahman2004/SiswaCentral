<?php
// PHP built-in dev server router
// Serves static files from /public/ and routes everything else to index.php
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Map the request to the public directory
$publicDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
$fullPath = $publicDir . str_replace('/', DIRECTORY_SEPARATOR, $uriPath);

// Serve static files directly
if ($uriPath !== '/' && is_file($fullPath)) {
    // Determine MIME type for proper content-type header
    $ext = strtolower(pathinfo($uriPath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css',
        'js'  => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg'=> 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff'=> 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf' => 'font/ttf',
        'json'=> 'application/json',
        'map' => 'application/json',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    readfile($fullPath);
    return true;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/public/index.php';
