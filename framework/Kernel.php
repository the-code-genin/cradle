<?php

namespace Cradle;

use Psr\Http\Message\ServerRequestInterface;

/**
 * An app kernel
 */
interface Kernel
{
    public function run(ServerRequestInterface $request = null);
}
