<?php

use Phinx\Migration\AbstractMigration;

class CreateAuthTokensTable extends AbstractMigration
{
    public function up()
    {
        $this->table("user_auth_tokens")
            ->addColumn("user_id", "biginteger", ["signed" => false])
            ->addColumn("token", "string")
            ->addColumn("created_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("updated_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->create();

        $this->table("user_auth_tokens")
            ->addIndex(["user_id"], ["name" => "USER_AUTH_TOKENS"])
            ->addIndex(["created_at"], ["name" => "USER_AUTH_TOKENS_CREATED_AT"])
            ->addIndex(["updated_at"], ["name" => "USER_AUTH_TOKENS_UPDATED_AT"])
            ->save();
    }

    public function down()
    {
        $this->table("auth_tokens")
            ->drop();
    }
}
