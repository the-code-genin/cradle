<?php
namespace App\Config;

/**
 * Defines the routes to be used by the application.
 * None of the keys found here should be removed or else the framework will crash.
 */
const ROUTES = [
	// The URI shorthands
	// The keys must not contain any regex patterns
	// The values must be valid regex patterns
	'shorthand' => [
		'text' => '[a-z0-9~%:_\-\.\+]+',
		'num' => '[0-9]+',
	],

	// The routing rules
	// The keys must be valid regex patterns and can contain shorthands
	// To use a shorthand: {shorthand_key} i.e "/hello/{text}/world"
	'routes' => [
		'/' => 'Home@index', // Your home page
	],

	'404_error' => 'Home@error404', // The 404 error route

	'maintenance' => 'Home@maintenance', // The maintenance route
];
