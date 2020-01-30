<?php

use Cradle\Logger;
use DI\Container;
use Slim\Factory\AppFactory;
use Cradle\ViewCompiler;
use Psr\Container\ContainerInterface;

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


// Ensure the server is running the minimum allowed PHP version.
// Current minimum: 7.2
if (version_compare(PHP_VERSION, '7.2', '<')) {
	http_response_code(503);
	echo "<b>Error:</b> Cradle requires a minimum PHP version of 7.2 to run.<br/><b>Current PHP version:</b> PHP_VERSION";
	exit(1);
}


// Define file structure.
define('BASE_DIR', __DIR__); // Define the base directory
define('PUBLIC_DIR', BASE_DIR . '/public'); // Define the public directory
define('RESOURCES_DIR', BASE_DIR . '/resources'); // Define resources directory
define('STORAGE_DIR', BASE_DIR . '/storage'); // Define the storage directory


// Include the composer autoloader.
require_once BASE_DIR . '/vendor/autoload.php';


// Load environment values from the .env file if a .env file exists.
if (file_exists(BASE_DIR . '/.env')) {
	\Dotenv\Dotenv::createImmutable(BASE_DIR)->load();
}


// Set up error handling based on app environment configuration
switch (getenv('APP_ENVIRONMENT')) { // Configure the exceptions and error logging levels based on the environment configuration
	case 'development': // All errors and exceptions are reported in development mode
		error_reporting(E_ALL);
		ini_set('DISPLAY_ERRORS', 'stdout');
		define('SHOW_ERRORS', true);
	break;

	case 'maintenance': // Site maintenance mode
		// Fall through

	case 'production': // No errors and exceptions are reported in production mode
		error_reporting(0);
		ini_set('DISPLAY_ERRORS', 'stderr');
		define('SHOW_ERRORS', false);
	break;

	default: // In case the environment was incorrectly set
		http_response_code(503);
		echo '<b>Error:</b> The application environment is not set correctly.';
		exit(1);
	break;
}


// Create a dependency container.
$container = new Container();


// Add the view compiler object.
$container->set('view', function (ContainerInterface $container) use (&$viewCompiler) {
    return new ViewCompiler();
});

// Add the logger object.
$container->set('logger', function (ContainerInterface $container) use (&$logger) {
    return new Logger();
});


// Create a new slim app.
$app = AppFactory::createFromContainer($container);


// Return the new app instance
return $app;
