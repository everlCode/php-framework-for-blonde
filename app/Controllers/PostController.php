<?php

namespace App\Controllers;

use Everl\Framework\Http\Response;

class PostController
{
    public function show(int $id)
    {
        $content = "<h1>Post - {$id} </h1>";

        return new Response($content);
    }
}