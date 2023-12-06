<?php

namespace Everl\Framework\Controller;

use Everl\Framework\Http\Response;
use League\Container\Container;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $view, array $parameteres = [], Response $response = null): Response
    {
        /**@var Environment $twig*/
        $twig = $this->container->get(Environment::class);

        $content = $twig->render($view, $parameteres);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}
