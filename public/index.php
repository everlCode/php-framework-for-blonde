<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH.'/vendor/autoload.php';

use Everl\Framework\Http\Kernel;
use Everl\Framework\Http\Request;
use Everl\Framework\Routing\Router;

$request = Request::createFromGlobals();


$kernel = new Kernel(new Router());

$response = $kernel->handle($request);

$response->send();
