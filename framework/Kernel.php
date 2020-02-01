<?php

namespace Cradle;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * An app kernel interface.
 */
interface Kernel
{
    /**
     * Handle a server request.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request = null): ResponseInterface;
}
