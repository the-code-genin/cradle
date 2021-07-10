<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        DB::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->enum('status', ['active', 'banned'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::schema()->drop('users');
    }
}
