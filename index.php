<?php
/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * A lightweight and extensible framework with the aim to avoid working with monolithic web apps.
 * Cradle is aimed at being easily extensible and as such only ships with the basic functions of autoloading,
 * error handling, URI routing and dynamic page generation.
 *
 * @package Cradle
 * @author Adekunle Mohammed Iyiola
 */


// Output buffering is started
ob_start();

// The current session is started
session_start();


/**
 * ------------------------------------------------------------------
 * BOOTSTRAPPING
 * ------------------------------------------------------------------
 *
 * The autoloader functions and the configuration object are loaded into the environment.
 */

// The autoloading functions and CONFIG object are loaded
require_once __DIR__ . '/application/config.php';
require_once __DIR__ . '/vendors/autoload.php';


/*
 * ------------------------------------------------------------------
 * ERROR HANDLING
 * ------------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default 'development' will show all errors but 'production' will hide them.
 */

switch (Cradle\Application\CONFIG['environment']) {
	case 'development': // All errors are reported
		error_reporting(E_ALL);
		ini_set('display_errors', 'stdout');
	break;

	case 'production': // No errors are reported
		ini_set('display_errors', 'stderr');
		error_reporting(0);
	break;

	default: // Incase the environment was incorrectly set
		http_response_code(503);
		echo '<b>Fatal Error:</b> The application environment is not set correctly.';
		exit(1);
}


/**
 * ------------------------------------------------------------------
 * URI ROUTING
 * ------------------------------------------------------------------
 *
 * The request URI is mapped to a defined route.
 */

// Get the routing rule to be used to handle the request
$rule = Cradle\Core\Router::getRouteRule();

// Resolve the routing rule
try {
	$route = Cradle\Core\Router::parseRouteRule($rule);
} catch (Exception $e) {
	echo "<b>Fatal Error:</b> {$e->getMessage()}";
	http_response_code(503);
	exit(1);
}

// Construct the eval string to point to the requested controller
$controllerClass = 'Cradle\\Application\\Controllers\\' . $route['controller'];
$controller = new $controllerClass;
$controllerMethod = $route['method'] . '(';
if (count($route['parameters'])) {
	$controllerMethod .= implode(',', $route['parameters']);
}

// Instantiate the requested controller
try {
	eval('$controller->' . "$controllerMethod);");
} catch (Exception $e) {
	echo "<b>Fatal Error:</b> {$e->getMessage()}";
	http_response_code(503);
	exit(1);
}


/**
 * ------------------------------------------------------------------
 * RESPONSE
 * ------------------------------------------------------------------
 *
 * Get and send response to the client.
 */

// Get the final output to be sent to the client
$output = $controller->getOutput();

// Send final output to the client
echo $output;
ob_end_flush();

// Exit successfully
exit(0);
