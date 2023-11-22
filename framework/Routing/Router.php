<?php

namespace Everl\Framework\Routing;

use Everl\Framework\Http\Exceptions\MethodNotAllowedException;
use Everl\Framework\Http\Exceptions\RouteNotFoundException;
use Everl\Framework\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controller, $method] = $handler;

            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routheInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath(),
        );

        switch ($routheInfo[0]) {
            case Dispatcher::FOUND:
                return [$routheInfo[1], $routheInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(',', $routheInfo[1]);

                $e = new MethodNotAllowedException( "Supported HTTP methods: $allowedMethods");
                $e->setStatusCode(405);
                throw $e;
            default:
                $e = new RouteNotFoundException('Route not found');
                $e->setStatusCode(404);
                throw $e;
        }
    }
}