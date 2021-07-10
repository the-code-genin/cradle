<?php

use Slim\App;
use Controllers\HomeController;

/**
 * Routing rules are defined here.
 * 
 * @var App $app
 */

$app->get('/', HomeController::class.':index');
