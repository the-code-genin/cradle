<?php

use App\Controllers\Home;

/**
 * Routing rules are defined here.
 */

$app->get('/', Home::class.':index');
