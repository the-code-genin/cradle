<?php

namespace Lib;

use Psr\Http\Message\ResponseInterface as Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewRenderer
{
    /**
     * Render a view file from the views directory
     *
     * @param string $path The path of the view file relative to the views directory
     * @param array $args Arguments to pass to the view file
     * @return string
     */
    public static function render(string $path, array $args = []): string
    {
        $loader = new FilesystemLoader(dirname(__DIR__) . '/views');
        $twig = new Environment($loader, []);
        return $twig->render($path, $args);
    }

    public static function generateViewResponse(Response $response, string $path, array $args = [])
    {
        $response->getBody()->write(ViewRenderer::render($path, $args));
        return $response;
    }
}
