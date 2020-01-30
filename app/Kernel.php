<?php

namespace App;

use Slim\App;
use Cradle\Kernel as CradleKernel;
use Cradle\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Representation of an abstract kernel
 */
abstract class Kernel implements CradleKernel
{
    /** @var App $app The slim app instance. */
    protected $app;

    /** @var array $middleware The middlewares to be registered for all routes for every request. */
    protected $middleware = [];

    /** @var string $routesFile The path(s) to the routes file(s) to be used by the kernel. */
    protected $routesFiles = [];

    /**
     * @param App $app The slim app instance to be used by the kernel.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Register middlewares to be used by the app for all routes for every request.
     * These middleware are added in the order in which they were specified.
     *
     * @return App
     */
    protected function registerMiddleWare(): App
    {
        foreach ($this->middleware as $middleware) {
            $this->app->add($middleware);
        }

        return $this->app;
    }

    /**
     * Include the route files to be used by the app
     *
     * @return void
     */
    protected function includeRouteFiles(): void
    {
        $app = $this->app;
        foreach ($this->routesFiles as $file) {
            require_once RESOURCES_DIR . '/routes/' . $file;
        }
    }

    /**
     * Run the kernel.
     *
     * @param \Psr\Http\Message\ServerRequestInterface|null $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request = null): ResponseInterface
    {
        // Register middleware
        $this->registerMiddleWare();

        // Include the routes to be used by the app
        $this->includeRouteFiles();

        // Register error handler
        $logger = new Logger($this->app);
        $errorMiddleware = $this->app->addErrorMiddleware(SHOW_ERRORS, true, true);
        $errorMiddleware->setDefaultErrorHandler($logger);

        // Handle the request
        $response = $this->app->handle($request);
        return $response;
    }
}
