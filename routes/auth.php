<?php

use Slim\App;
use Middleware\CORSMiddleware;
use Controllers\AuthController;
use Middleware\UserAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

/**
 * Routing rules are defined here.
 * 
 * @var App $app
 */

$app->group('/api/auth', function (RouteCollectorProxy $group) {
    $group->post('/signup', AuthController::class.':signup');
    $group->post('/login', AuthController::class.':login');
    $group->get('/me', AuthController::class.':getMe')->addMiddleware(new UserAuthMiddleware);
    $group->patch('/me', AuthController::class.':updateMe')->addMiddleware(new UserAuthMiddleware);
    $group->post('/logout', AuthController::class.':logout')->addMiddleware(new UserAuthMiddleware);
});
