<?php
namespace App\Controllers;

use Lib\ViewRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    /**
     * The index route.
     */
    protected function index(Request $request, Response $response, array $args)
    {
        $response->getBody()->write(ViewRenderer::render('home.twig'));
        return $response;
    }
}
