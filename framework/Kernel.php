<?php

namespace Cradle;

use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface;

/**
 * An app kernel interface
 */
interface Kernel
{
    public function run(App $app, ServerRequestInterface $request = null): ResponseInterface;
}
