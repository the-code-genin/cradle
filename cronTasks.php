<?php

use Crunz\Schedule;

// Require autoloader.
require_once __DIR__ .'/vendor/autoload.php';

// Set default time zone.
date_default_timezone_set('UTC');

// Set the schedules.
$schedule = new Schedule();

return $schedule;
