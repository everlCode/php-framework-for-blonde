<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use Everl\Framework\Routing\Route;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::get('/posts/{id}', [PostController::class, 'show']),
  Route::get('/hi/{name}', function ($name) {
      return new \Everl\Framework\Http\Response("Hello, $name");
  }),
];
