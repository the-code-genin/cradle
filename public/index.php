<?php

use App\HTTPKernel;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * Cradle is an MVC microframework for building web apps with PHP.
 *
 * It is made with the aim to help php developers avoid working with spaghetti code
 * and embrace the MVC software architecture in as little time as possible.
 * It is totally free to use and open source.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle (Iyiola) <adekunle3317@gmail.com>
 */



// Bootstrap cradle
$app = require_once __DIR__ . '/bootstrap.php';


// Create request
$requestFactory = ServerRequestCreatorFactory::create();
$request = $requestFactory->createServerRequestFromGlobals();


// Handle request
$kernel = new HTTPKernel($app);
$response = $kernel->handle($request);


// Send response
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);

exit(0); // Exit successfully
