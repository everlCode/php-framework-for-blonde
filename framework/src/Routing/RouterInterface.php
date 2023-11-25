<?php

namespace Everl\Framework\Routing;

use Everl\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}