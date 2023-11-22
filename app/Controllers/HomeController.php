<?php

namespace App\Controllers;

use Everl\Framework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        $content = '<h1> Hello motherfaca</h1>';

        return new Response($content);
    }
}