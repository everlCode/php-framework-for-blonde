<?php

namespace Everl\Framework\Container;

use Everl\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    public function add(string $id, string|object $concrete = null)
    {
        if (is_null($concrete)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id not found");
            }
            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }
    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved");
            }

            $this->add($id);
        }

        $instance = $this->resolve($this->services[$id]);

        return $instance;
    }

    private function resolve($class)
    {
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return $reflection->newInstance();
        }

        $parametres  = $constructor->getParameters();
        $classDependecies = $this->resolveClassDependecies($parametres);


        return $reflection->newInstanceArgs($classDependecies);
    }

    private function resolveClassDependecies(array $constructorParams): array
    {
        $parametersInstances = [];
        /* @var \ReflectionParameter $param*/
        foreach ($constructorParams as $param) {
            $serviceType = $param->getType();
            $service = $this->get($serviceType->getName());
            $parametersInstances[] = $service;
        }

        return $parametersInstances;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}