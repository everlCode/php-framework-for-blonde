<?php

use Doctrine\DBAL\Connection;
use Everl\Framework\Console\Application;
use Everl\Framework\Console\Commands\MigrateCommand;
use Everl\Framework\Controller\AbstractController;
use Everl\Framework\Dbal\ConnectionFactory;
use Everl\Framework\Http\Kernel;
use Everl\Framework\Routing\Router;
use Everl\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\ObjectArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Everl\Framework\Console\Kernel as ConsoleKernel;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');
//App params
$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH . '/views';
$databaseUrl = 'pdo-mysql://lemp:lemp@database:3306/lemp?charset=utf8mb4';

//App services
$container = new Container();
$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('Everl\\Framework\\Console\\Commands\\'));
$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('twig-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($viewsPath));

$container->addShared(Environment::class)
    ->addArgument('twig-loader');

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
   return $container->get(ConnectionFactory::class)->create();
});

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add(Application::class)
    ->addArgument($container);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class);

return $container;