<?php

namespace Database\Repositories;

use Database\Entities\User;
use Pixie\QueryBuilder\QueryBuilderHandler;

class Users
{
    /**
     * @return QueryBuilderHandler
     */
    private static function getQueryBuilder()
    {
        return $GLOBALS["query_builder"];
    }

    /**
     * @return User|null
     */
    public static function getUserById(int $id)
    {
        return Users::getQueryBuilder()
            ->table("users")
            ->where("id", $id)
            ->asObject(User::class)
            ->first();
    }
    /**
     * @return User|null
     */
    public static function getUserByEmail(string $email)
    {
        return Users::getQueryBuilder()
            ->table("users")
            ->where("email", $email)
            ->asObject(User::class)
            ->first();
    }

    /**
     * @return int
     */
    public static function getUsersWithEmailCount(string $email)
    {
        return Users::getQueryBuilder()
            ->table("users")
            ->where("email", $email)
            ->count();
    }

    /**
     * @return bool
     */
    public static function checkUserHasAuthToken(int $userId, string $token)
    {
        return Users::getQueryBuilder()
            ->table("user_auth_tokens")
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

        Users::getQueryBuilder()
            ->table("user_auth_tokens")
            ->insert([
                "user_id" => $userId,
                "token" => $token
            ]);
    }

    /**
     * @return User
     */
    public static function insert(User $user) {
        $insertId = Users::getQueryBuilder()
            ->table("users")
            ->insert((array) $user);
        return Users::getUserById($insertId);
    }

    /**
     * @return User
     */
    public static function updateById(int $userId, User $user) {
        Users::getQueryBuilder()
            ->table("users")
            ->where("id", $userId)
            ->update((array) $user);
        return Users::getUserById($userId);
    }

    public static function toJSON(User $user) {
        $data = get_object_vars($user);
        unset($data["password"]);
        return $data;
    }
}
