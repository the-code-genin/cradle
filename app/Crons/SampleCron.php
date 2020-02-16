<?php

namespace App\Crons;

use Cradle\Cron;

class SampleCron extends Cron
{
    /**
     * A sample cron that is run every minute and returns no values.
     *
     * @param object $params
     * @return void
     */
    protected function everyMinute(): void
    {
        // Code.
    }
}
