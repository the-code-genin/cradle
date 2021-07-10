<?php
namespace App\Controllers;

use Lib\ViewRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
