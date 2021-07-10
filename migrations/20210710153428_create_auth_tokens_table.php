<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

class CreateAuthTokensTable extends AbstractMigration
{
    public function up()
    {
        DB::schema()->create('auth_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('token');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        DB::schema()->drop('auth_tokens');
    }
}
