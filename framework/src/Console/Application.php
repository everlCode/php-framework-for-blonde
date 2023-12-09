<?php

namespace Everl\Framework\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if (!$commandName) {
            throw new ConsoleExceptions('Ivalid command name!');
        }

        if (!$this->container->has("console:" . $commandName)) {
            throw new ConsoleExceptions('Command not found!');
        }

        /*** @var CommandInterface $command ***/
        $command = $this->container->get("console:" . $commandName);
        $parametres = array_slice($argv, 2);
        $options = $this->parseOptions($parametres);

        $status = $command->execute($parametres);

        return $status;
    }

    private function parseOptions(array $args)
    {
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}