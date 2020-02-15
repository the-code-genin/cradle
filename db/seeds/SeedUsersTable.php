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
        $hex = bin2hex(random_bytes(64));

        $user = new User;
        $user->username = $this->faker->username;
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->auth_token = password_hash($hex, PASSWORD_DEFAULT) . '.' . $hex;
        $user->save();
    }
}
