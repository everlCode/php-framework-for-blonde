<?php

namespace Everl\Framework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $app
    )
    {
    }

    public function handle(): int
    {
        $this->registerCommands();

        $status = $this->app->run();
        dd($status);

        return 0;
    }

    private function registerCommands(): void
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $nameSpace = $this->container->get('framework-commands-namespace');
        /*** @var \DirectoryIterator $file ***/
        foreach ($commandFiles as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $command = $nameSpace . pathinfo($file, PATHINFO_FILENAME);

            if (!is_subclass_of($command, CommandInterface::class)) {
                continue;
            }
            $reflection = new \ReflectionClass($command);
            $name = $reflection->getProperty('name')
                ->getDefaultValue();

            $this->container->add("console:$name", $command);
        }
    }
}