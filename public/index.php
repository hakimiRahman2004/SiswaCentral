<?php

require_once __DIR__ . '/../app/Core/env.php';
require_once __DIR__ . '/../app/Core/helpers.php';

loadEnv(__DIR__ . '/../.env');

if (config('app.debug')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;

Session::start();

$request = Request::capture();

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch($request);
