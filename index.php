<?php

/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * Cradle is an MVC microframework for building web apps with PHP.
 *
 * It is made with the aim to help php developers avoid working with spaghetti code
 * and embrace the MVC software architecture in as little time as possible. It is totally free to use and open source.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle (Iyiola) <adekunle3317@gmail.com>
 */

// Ensure the server is running the minimum allowed PHP version.
// Current minimum: 7.2
if (version_compare(PHP_VERSION, '7.2', '<')) {
	http_response_code(503);
	echo "<b>Error:</b> Cradle requires a minimum PHP version of 7.2 to run.<br/><b>Current PHP version:</b> PHP_VERSION";
	exit(1);
}

define('BASE_DIR', __DIR__); // Define the base directory

require_once BASE_DIR . '/vendor/autoload.php'; // Include the composer autoloader

Dotenv\Dotenv::createImmutable(BASE_DIR)->load(); // Load environment values from the .env file

require_once BASE_DIR . '/public/index.php'; // Fire up cradle...
