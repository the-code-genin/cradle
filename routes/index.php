<?php

use Slim\App;
use Middleware\CORSMiddleware;

/**
 * Require all route config files
 * 
 * @var App $app
 */

$app->addMiddleware(new CORSMiddleware());

require_once __DIR__ . '/home.php';
require_once __DIR__ . '/auth.php';