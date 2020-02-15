<?php

namespace Cradle;

use Phinx\Seed\AbstractSeed;
use Faker\{Factory, Generator};
use \Illuminate\Database\Capsule\Manager as Capsule;

abstract class Seed extends AbstractSeed
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    protected $capsule;

    /** @var Generator $factory */
    protected $faker;

    public function init()
    {
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
        $this->faker = Factory::create();
    }
}
