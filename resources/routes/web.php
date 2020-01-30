<?php

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

/**
 * Routing rules are defined here.
 */

/** @var App $app The slim application instance. */
$app;


$app->get('/', function(RequestInterface $request, ResponseInterface $response, $args) {
    $response->getBody()->write('Hello world!');
    return $response->withHeader('Content-Type', 'text/plain');
});
