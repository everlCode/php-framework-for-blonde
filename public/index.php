<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH.'/vendor/autoload.php';

use Everl\Framework\Http\Kernel;
use Everl\Framework\Http\Request;
use League\Container\Container;

$request = Request::createFromGlobals();

/* @var Container $container */
$container = require BASE_PATH . '/config/services.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$container = new Container();

$response->send();
