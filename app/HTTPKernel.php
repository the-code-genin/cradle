<?php

namespace App;

class HTTPKernel extends Kernel
{
    /** @var array $middleware The middlewares to be registered for all routes for every request. */
    protected $middlewares = [

    ];

    /** @var string $routesFile The path(s) to the routes file(s) to be used by the kernel. */
    protected $routesFiles = [
        'web.php',
    ];
}
