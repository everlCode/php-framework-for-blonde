<?php

namespace Everl\Framework\Tests;

class Somecode
{
    public function __construct(
        private readonly Foo $foo
    )
    {
        
    }

    public function getFoo()
    {
        return $this->foo;
    }
}
