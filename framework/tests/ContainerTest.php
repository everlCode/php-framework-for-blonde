<?php

namespace Everl\Framework\Tests;

use Everl\Framework\Container\Container;
use Everl\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container();

        $container->add('everl-class', TestClass::class);

        $this->assertInstanceOf(TestClass::class, $container->get('everl-class'));
    }

    public function test_container_has_exception_ContainerException_if_add_wrong_service()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);
        $container->add('no-class');
    }

    public function test_has_method()
    {
        $container = new Container();

        $container->add('everl-class', TestClass::class);

        $this->assertTrue($container->has('everl-class'));
        $this->assertFalse($container->has('no-class'));
    }

    public function test_recursively_autowired()
    {
        $container = new Container();

//        $container->add('somecode', \Somecode::class);

        $this->assertInstanceOf(Foo::class, $container->get(Somecode::class)->getFoo());
    }
}