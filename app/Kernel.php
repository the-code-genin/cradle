<?php

namespace App;

use Slim\App;
use Cradle\View;
use Cradle\ViewCompiler;
use Cradle\Kernel as CradleKernel;
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
    protected $middlewares = [];

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
        foreach ($this->middlewares as $middlewareClass) {
            $middleware = new $middlewareClass;
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
     * To be called before app handling is done.
     * To be defined in a kernel.
     * 
     * @return void
     */
    abstract protected function boot(): void;

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

        // Register error handler
        $logger = $this->app->getContainer()->get('logger');
        $errorMiddleware = $this->app->addErrorMiddleware(SHOW_ERRORS, true, true);
        $errorMiddleware->setDefaultErrorHandler($logger);

        // Any miscellenous configuration in the kernel.
        $this->boot();

        // Include the routes to be used by the app.
        $this->includeRouteFiles();

        // Handle the request
        if (getenv('APP_ENVIRONMENT') != 'maintenance') {
            $response = $this->app->handle($request);
        } else {
            /** @var ViewCompiler $viewCompiler A view compiler. */
            $viewCompiler = $this->app->getContainer()->get('view');
			$viewCompiler->addView(new View('framework/maintenance.twig'));

            $response = $this->app->getResponseFactory()->createResponse();
            $response->getBody()->write($viewCompiler->compileViews());
        }

        return $response;
    }
}
