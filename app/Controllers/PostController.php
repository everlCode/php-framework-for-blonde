<?php

namespace App\Controllers;

use Everl\Framework\Controller\AbstractController;
use Everl\Framework\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        $content = $this->render(
            'posts.html.twig',
            ['postId' => $id]
        );

        return $content;
    }

    public function create(): Response
    {
        $content = $this->render(
            'create_post.html.twig'
        );

        return $content;
    }
}