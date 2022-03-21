<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        $this->table("users")
            ->addColumn("name", "string")
            ->addColumn("email", "string")
            ->addColumn("password", "string")
            ->addColumn("status", "enum", ["values" => ["active", "banned"], "default" => "active"])
            ->addColumn("created_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("updated_at", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->create();

        $this->table("users")
            ->addIndex(["email"], ["unique" => true, "name" => "USERS_EMAIL"])
            ->addIndex(["created_at"], ["name" => "USERS_CREATED_AT"])
            ->addIndex(["updated_at"], ["name" => "USERS_UPDATED_AT"])
            ->save();
    }

    public function down()
    {
        $this->table("users")
            ->drop();
    }
}
