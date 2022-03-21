<?php

use Dotenv\Dotenv;
use Pixie\Connection;

require __DIR__ . '/vendor/autoload.php';

// Load env variables
if (file_exists(__DIR__ . '/.env')) Dotenv::createImmutable(__DIR__)->load();

// Set timezone
date_default_timezone_set("UTC");

// Connect to the database
new Connection(
	"mysql", 
	[
		"driver" => getenv('DB_DRIVER'),
		"host" => sprintf("%s:%s", getenv('DB_HOST'), getenv('DB_PORT')),
		"database" => getenv('DB_NAME'),
		"username" => getenv('DB_USERNAME'),
		"password" => getenv('DB_PASSWORD'),
	]
);

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT')
        ]
    ],
    'version_order' => 'creation'
];
