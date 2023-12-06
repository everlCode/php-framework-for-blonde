<?php

namespace App\Controllers;

use App\Services\YouTubeService;
use Everl\Framework\Controller\AbstractController;
use Everl\Framework\Http\Response;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(
        private YouTubeService $service,
        private Environment $twig,
    )
    {
    }

    public function index(): Response
    {
        return $this->render('home.html.twig', ['link' => $this->service->getChannelUrl()]);
    }
}