<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/vendor/autoload.php';

// Load env variables
if (file_exists(__DIR__ . '/.env')) Dotenv::createImmutable(__DIR__)->load();

// Connect to the database
$db = new DB;
$db->addConnection([
	'driver'    => getenv('DB_DRIVER'),
	'host'      => getenv('DB_HOST'),
	'port'      => getenv('DB_PORT'),
	'database'  => getenv('DB_NAME'),
	'username'  => getenv('DB_USERNAME'),
	'password'  => getenv('DB_PASSWORD'),
]);

$db->setAsGlobal();
$db->bootEloquent();

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
