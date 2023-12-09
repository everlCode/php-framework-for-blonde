<?php

namespace Everl\Framework\Http;

use Doctrine\DBAL\Connection;
use Everl\Framework\Http\Exceptions\HttpException;
use Everl\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class Kernel
{
    private string $appEnv = 'local';
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
        $this->appEnv = $container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            dd($this->container->get(Connection::class)->connect());
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response =  call_user_func_array($routeHandler, $vars);
        } catch(HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch(\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
    }

    private function createExceptionResponse(\Exception $e): Response
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        }

        return new Response('Server error', 500);
    }
}