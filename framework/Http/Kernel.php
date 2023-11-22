<?php

namespace Everl\Framework\Http;

use Everl\Framework\Http\Exceptions\HttpException;
use Everl\Framework\Http\Exceptions\MethodNotAllowedException;
use Everl\Framework\Http\Exceptions\RouteNotFoundException;
use Everl\Framework\Routing\RouterInterface;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Kernel
{

    public function __construct(private RouterInterface $router)
    {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
            $response =  call_user_func_array($routeHandler, $vars);
        } catch(HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch(\Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }

        return $response;
    }
}