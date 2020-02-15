<?php

namespace Cradle;

use Dotenv\Dotenv;
use Phinx\Migration\AbstractMigration;
use \Illuminate\Database\Capsule\Manager as Capsule;

class Migration extends AbstractMigration
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;

    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    public function init()
    {
        // Load environment values from the .env file if a .env file exists.
        define('BASE_DIR', '../'); // Define the base directory
        if (file_exists(BASE_DIR . '/.env')) {
            Dotenv::createImmutable(BASE_DIR)->load();
        }

        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver'    => getenv('DB_DRIVER') ? getenv('DB_DRIVER') : 'mysql',
            'host'      => getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost',
            'port'      => getenv('DB_PORT') ? getenv('DB_PORT') : '3306',
            'database'  => getenv('DB_NAME') ? getenv('DB_NAME') : 'test',
            'username'  => getenv('DB_USERNAME') ? getenv('DB_USERNAME') : 'root',
            'password'  => getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
