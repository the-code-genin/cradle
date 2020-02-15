<?php

use Dotenv\Dotenv;
use Cradle\Migration;

require __DIR__ . '/vendor/autoload.php';

// Load environment values from the .env file if a .env file exists.
if (file_exists(BASE_DIR . '/.env')) {
    Dotenv::createImmutable(BASE_DIR)->load();
}

return
[
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds'
    ],
    'migration_base_class' => Migration::class,
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => getenv('DB_DRIVER') ? getenv('DB_DRIVER') : 'mysql',
            'host' => getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost',
            'name' => getenv('DB_NAME') ? getenv('DB_NAME') : 'test',
            'user' => getenv('DB_USERNAME') ? getenv('DB_USERNAME') : 'root',
            'pass' => getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : '',
            'port' => getenv('DB_PORT') ? getenv('DB_PORT') : '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];