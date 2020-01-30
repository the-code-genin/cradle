<?php

namespace Cradle;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * An app kernel interface
 */
interface Kernel
{
    /**
     * Run the kernel.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request = null): ResponseInterface;
}
