<?php

namespace App;

use App\Middleware\SampleMiddleware;

/**
 * This kernel serves HTTP requests.
 */
class HTTPKernel extends Kernel
{
    /** @var array $middleware The middleware classes to be registered for all routes for every request. */
    protected $middlewares = [
        SampleMiddleware::class,
    ];

    /** @var string $routesFile The path(s) to the routes file(s) to be used by the kernel. */
    protected $routesFiles = [
        'web.php',
    ];

    /**
     * To be called before app handling is done.
     * To be defined in a kernel.
     * 
     * @return void
     */
    protected function boot(): void
    {

    }
}
