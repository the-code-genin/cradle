<?php

use App\Models\User;
use Cradle\Seed;

class SeedUsersTable extends Seed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $user = new User;
        $user->username = $this->faker->name;
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->auth_token = md5('auth_token');
        $user->save();
    }
}
