<?php

use Slim\App;
use Crunz\Schedule;

/**
 * Bootstrap cradle.
 * @var App $app
 */
require_once __DIR__ .'/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap.php';


// Set the schedules.
$schedule = new Schedule();



return $schedule;
