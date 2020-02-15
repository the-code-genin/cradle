<?php

use Crunz\Schedule;

// Require autoloader.
require_once __DIR__ .'/vendor/autoload.php';


// Load environment values from the .env file if a .env file exists.
if (file_exists(BASE_DIR . '/.env')) {
	\Dotenv\Dotenv::createImmutable(BASE_DIR)->load();
}


// Set default timezone for app to UTC.
date_default_timezone_set(getenv('TIME_ZONE') ? getenv('TIME_ZONE') : 'UTC');

// Set the schedules.
$schedule = new Schedule();

return $schedule;
