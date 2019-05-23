<?php
namespace Cradle\Application\Config;

/**
 * The routes to be used by the application
 */

const ROUTES = [
	// The URI shorthands
	'shorthand' => [
		'any' => 'a-z0-9~%:_\-\.\+',
		'num' => '0-9',
	],

	// The routing rules
	'routes' => [
		'/' => 'MainController/index',
	],

	// The 404 error route
	'404_error' => 'MainController/error404',

	// The maintenance route
	'maintenance' => 'MainController/maintenance',
];
