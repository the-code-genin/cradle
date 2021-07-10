<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthTokensTable extends AbstractMigration
{
    public function up()
    {
        Manager::schema()->create('auth_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('token');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Manager::schema()->drop('auth_tokens');
    }
}
