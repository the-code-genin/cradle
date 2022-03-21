<?php

namespace Database\Entities;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $status;
    public string $created_at;
    public string $updated_at;
}
