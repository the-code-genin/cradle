<?php

use App\Crons\SampleCron;
use Crunz\Schedule;

// Require autoloader
require dirname(dirname(__DIR__)) . '/bootstrap.php';


// Set the schedules.
$schedule = new Schedule();


$schedule->run(function() {
    $cron = new SampleCron();
    $cron->everyMinute();
})->everyMinute()->description('A sample cron.');


return $schedule;
