<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH.'/vendor/autoload.php';

use Everl\Framework\Container\Container;
use Everl\Framework\Http\Kernel;
use Everl\Framework\Http\Request;
use Everl\Framework\Routing\Router;
use Everl\Framework\Tests\Somecode;
use Everl\Framework\Tests\Foo;

$request = Request::createFromGlobals();


$kernel = new Kernel(new Router());

$response = $kernel->handle($request);

$container = new Container();

$container->add('foo', Foo::class);
$container->add('somecode', Somecode::class);


dd($container->get(\Everl\Framework\Tests\Boo::class));
$response->send();
