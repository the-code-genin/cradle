<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        Manager::schema()->create('users', function (Blueprint $table) {
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
        Manager::schema()->drop('users');
    }
}
