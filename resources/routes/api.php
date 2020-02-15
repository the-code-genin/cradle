<?php

use Slim\App;
use App\Controllers\Api\Home;
use App\Middleware\CORSMiddleware;
use Slim\Routing\RouteCollectorProxy;

/**
 * Routing rules are defined here.
 * 
 * @var App $app
 */

$app->group('/api/v0', function (RouteCollectorProxy $group) {
    $group->any('[{any:.*}]', Home::class.':index');
})->add(new CORSMiddleware);
