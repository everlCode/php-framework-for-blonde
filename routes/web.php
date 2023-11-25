<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use Everl\Framework\Http\Response;
use Everl\Framework\Routing\Route;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::get('/posts/{id}', [PostController::class, 'show']),
  Route::get('/hi/{name}', function ($name) {
      return new Response("Hello, $name");
  }),
];
