<?php

namespace Database\Repositories;

use Database\Entities\User;
use Pixie\QueryBuilder\QueryBuilderHandler as QB;

class Users
{
    /**
     * @return User|null
     */
    public static function getUserById(int $id)
    {
        return QB::table("users")
            ->where("id", $id)
            ->asObject(User::class)
            ->first();
    }
    /**
     * @return User|null
     */
    public static function getUserByEmail(string $email)
    {
        return QB::table("users")
            ->where("email", $email)
            ->asObject(User::class)
            ->first();
    }

    /**
     * @return int
     */
    public static function getUsersWithEmailCount(string $email)
    {
        return QB::table("users")
            ->where("email", $email)
            ->count();
    }

    /**
     * @return bool
     */
    public static function checkUserHasAuthToken(int $userId, string $token)
    {
        return QB::table("user_auth_tokens")
            ->where("user_id", $userId)
            ->where("token", $token)
            ->count() != 0;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function addUserAuthToken(int $userId, string $token) {
        if (Users::checkUserHasAuthToken($userId, $token)) {
            throw new \Exception("User already has this auth token.");
        }

        QB::table("user_auth_tokens")
            ->insert([
                "user_id" => $userId,
                "token" => $token
            ]);
    }

    /**
     * @return User
     */
    public static function insert(User $user) {
        $insertId = QB::table("users")
            ->insert($user);
        return Users::getUserById($insertId);
    }

    /**
     * @return User
     */
    public static function updateById(int $userId, User $user) {
        QB::table("users")
            ->where("id", $userId)
            ->update($user);
        return Users::getUserById($userId);
    }

    public static function toJSON(User $user) {
        $data = get_object_vars($user);
        unset($data["password"]);
        return $data;
    }
}
