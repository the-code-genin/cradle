<?php
namespace Cradle\Application;

/**
 * The global configuration object
 * Contains configuration information like routing rules, application constants and database connection configurations
 * Can also contain other information
 * The configuration information for core cradle classes like the routing rules should not be removed!
 */

define('_BASE_URL', (isset($_SERVER['HTTPS'])? 'https' : 'http') . "://{$_SERVER["HTTP_HOST"]}/");

const CONFIG = [
	// The application environment, options are('development', 'production')
	'environment' => 'development',

	// The site base url
	'base_url' => _BASE_URL,

	// The routing configurations
	'routing' => [
		// The allowed URI characters
		'allowed_uri_characters' => 'a-z0-9~%:_\-\.\+',

		// The routing rules
		'routes' => [
			'/' => 'MainController/index'
		],

		// The 404 error route
		'404_error' => 'MainController/error404'
	],

	// The database connection configuration
	'database' => [
		// Connection details for mariaDB and MySQL databases
		'mysql' => [
			'host' => 'localhost',
			'username' => '',
			'password' => '',
			'database' => ''
		],

		// Connection details for sqlite
		'sqlite' => [
			'db_file' => ''
		]
	]
];
