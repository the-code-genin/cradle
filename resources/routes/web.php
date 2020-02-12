<?php

use App\Controllers\Web\Home;

/**
 * Routing rules are defined here.
 */

$app->get('/', Home::class.':index');
